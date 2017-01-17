<?php
/**
 * partner service allows you to change/manage your partner personal details and settings as well
 *
 * @service partner
 * @package api
 * @subpackage services
 */
class PartnerService extends BorhanBaseService 
{
    
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'register') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}

	/**
	 * Create a new Partner object
	 * 
	 * @action register
	 * @param BorhanPartner $partner
	 * @param string $cmsPassword
	 * @param int $templatePartnerId
	 * @param bool $silent
	 * @return BorhanPartner
	 *
	 * @throws APIErrors::PARTNER_REGISTRATION_ERROR
	 */
	public function registerAction( BorhanPartner $partner , $cmsPassword = "" , $templatePartnerId = null, $silent = false)
	{
		BorhanResponseCacher::disableCache();
		
		$dbPartner = $partner->toPartner();
		
		$c = new Criteria();
		$c->addAnd(UserLoginDataPeer::LOGIN_EMAIL, $partner->adminEmail, Criteria::EQUAL);
		$existingUser = UserLoginDataPeer::doSelectOne($c);
		/*@var $exisitingUser UserLoginData */

		try
		{
			if ( $cmsPassword == "" ) {
				$cmsPassword = null;
			}
			
			
			$parentPartnerId = null;
			if ( $this->getKs() && $this->getKs()->isAdmin() )
			{
				$parentPartnerId = $this->getKs()->partner_id;
				if ($parentPartnerId == Partner::ADMIN_CONSOLE_PARTNER_ID) {
		                    $parentPartnerId = null;
				}
                else
                {
					// only if this partner is a var/group, allow setting it as parent for the new created partner
					$parentPartner = PartnerPeer::retrieveByPK( $parentPartnerId );
					if ( ! ($parentPartner->getPartnerGroupType() == PartnerGroupType::VAR_GROUP ||
							$parentPartner->getPartnerGroupType() == PartnerGroupType::GROUP ) )
					{
						throw new BorhanAPIException( BorhanErrors::NON_GROUP_PARTNER_ATTEMPTING_TO_ASSIGN_CHILD , $parentPartnerId );
					}
					
					if ($templatePartnerId)
					{
					    $templatePartner = PartnerPeer::retrieveByPK($templatePartnerId);
					    if (!$templatePartner || $templatePartner->getPartnerParentId() != $parentPartnerId)
					        throw new BorhanAPIException( BorhanErrors::NON_GROUP_PARTNER_ATTEMPTING_TO_ASSIGN_CHILD , $parentPartnerId );
					}
				}
			}
			
			$partner_registration = new myPartnerRegistration ( $parentPartnerId );
			
			$ignorePassword = false;
			if ($existingUser && ($this->getKs()->partner_id == Partner::ADMIN_CONSOLE_PARTNER_ID || $this->getKs()->partner_id == $parentPartnerId)){
				kuserPeer::setUseCriteriaFilter(false);
				$kuserOfLoginData = kuserPeer::getKuserByEmail($partner->adminEmail, $existingUser->getConfigPartnerId());
				kuserPeer::setUseCriteriaFilter(true);
				if ($kuserOfLoginData){
					$ignorePassword = true;
				}
			}
			
			list($pid, $subpid, $pass, $hashKey) = $partner_registration->initNewPartner( $dbPartner->getName() , $dbPartner->getAdminName() , $dbPartner->getAdminEmail() ,
				$dbPartner->getCommercialUse() , "yes" , $dbPartner->getDescription() , $dbPartner->getUrl1() , $cmsPassword , $dbPartner, $ignorePassword, $templatePartnerId );

			$dbPartner = PartnerPeer::retrieveByPK( $pid );

			// send a confirmation email as well as the result of the service
			$partner_registration->sendRegistrationInformationForPartner( $dbPartner , false, $existingUser, $silent );

		}
		catch ( Exception $ex )
		{
			BorhanLog::CRIT($ex);
			// this assumes the partner name is unique - TODO - remove key from DB !
			throw new BorhanAPIException( APIErrors::PARTNER_REGISTRATION_ERROR);
		}		
		
		$partner = new BorhanPartner(); // start from blank
		$partner->fromPartner( $dbPartner );
		$partner->secret = $dbPartner->getSecret();
		$partner->adminSecret = $dbPartner->getAdminSecret();
		$partner->cmsPassword = $pass;
		
		return $partner;
	}


	/**
	 * Update details and settings of an existing partner
	 * 
	 * @action update
	 * @param BorhanPartner $partner
	 * @param bool $allowEmpty
	 * @return BorhanPartner
	 *
	 * @throws APIErrors::UNKNOWN_PARTNER_ID
	 */	
	public function updateAction( BorhanPartner $partner, $allowEmpty = false)
	{
		$vars_arr=get_object_vars($partner);
		foreach ($vars_arr as $key => $val){
		    if (is_string($partner->$key)){
                        $partner->$key=strip_tags($partner->$key);
                    }    
                }   
		$dbPartner = PartnerPeer::retrieveByPK( $this->getPartnerId() );
		
		if ( ! $dbPartner )
			throw new BorhanAPIException ( APIErrors::UNKNOWN_PARTNER_ID , $this->getPartnerId() );
		
		try {
			$dbPartner = $partner->toUpdatableObject($dbPartner);
			$dbPartner->save();
		}
		catch(kUserException $e) {
			if ($e->getCode() === kUserException::USER_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			throw $e;
		}
		catch(kPermissionException $e) {
			if ($e->getCode() === kPermissionException::ACCOUNT_OWNER_NEEDS_PARTNER_ADMIN_ROLE) {
				throw new BorhanAPIException(BorhanErrors::ACCOUNT_OWNER_NEEDS_PARTNER_ADMIN_ROLE);
			}
			throw $e;			
		}		
		
		$partner = new BorhanPartner();
		$partner->fromPartner( $dbPartner );
		
		return $partner;
	}
	
	
	/**
	 * Retrieve partner object by Id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanPartner
	 *
	 * @throws APIErrors::INVALID_PARTNER_ID
	 */
	public function getAction ($id = null)
	{
	    if (is_null($id))
	    {
	        $id = $this->getPartnerId();
	    }
	    
	    $c = PartnerPeer::getDefaultCriteria();
	    
		$c->addAnd(PartnerPeer::ID ,$id);
		
		$dbPartner = PartnerPeer::doSelectOne($c);
		if (is_null($dbPartner))
		{
		    throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $id);
		}
		
		$partner = new BorhanPartner();
		$partner->fromObject($dbPartner, $this->getResponseProfile());
		
		return $partner;
	}

	/**
	 * Retrieve partner secret and admin secret
	 * 
	 * @action getSecrets
	 * @param int $partnerId
	 * @param string $adminEmail
	 * @param string $cmsPassword
	 * @return BorhanPartner
	 * 
	 *
	 * @throws APIErrors::ADMIN_KUSER_NOT_FOUND
	 */
	public function getSecretsAction( $partnerId , $adminEmail , $cmsPassword )
	{
		BorhanResponseCacher::disableCache();

		$adminKuser = null;
		try {
			$adminKuser = UserLoginDataPeer::userLoginByEmail($adminEmail, $cmsPassword, $partnerId);
		}
		catch (kUserException $e) {
			throw new BorhanAPIException ( APIErrors::ADMIN_KUSER_NOT_FOUND, "The data you entered is invalid" );
		}
		
		if (!$adminKuser || !$adminKuser->getIsAdmin()) {
			throw new BorhanAPIException ( APIErrors::ADMIN_KUSER_NOT_FOUND, "The data you entered is invalid" );
		}
		
		BorhanLog::log( "Admin Kuser found, going to validate password", BorhanLog::INFO );
		
		// user logged in - need to re-init kPermissionManager in order to determine current user's permissions
		$ks = null;
		kSessionUtils::createKSessionNoValidations ( $partnerId ,  $adminKuser->getPuserId() , $ks , 86400 , $adminKuser->getIsAdmin() , "" , '*' );
		kCurrentContext::initKsPartnerUser($ks);
		kPermissionManager::init();		
		
		$dbPartner = PartnerPeer::retrieveByPK( $partnerId );
		$partner = new BorhanPartner();
		$partner->fromPartner( $dbPartner );
		$partner->cmsPassword = $cmsPassword;
		
		return $partner;
	}
	
	/**
	 * Retrieve all info attributed to the partner
	 * This action expects no parameters. It returns information for the current KS partnerId.
	 * 
	 * @action getInfo
	 * @return BorhanPartner
	 * @deprecated
	 * @throws APIErrors::UNKNOWN_PARTNER_ID
	 */		
	public function getInfoAction( )
	{
		return $this->getAction();
	}
	
	/**
	 * Get usage statistics for a partner
	 * Calculation is done according to partner's package
	 *
	 * Additional data returned is a graph points of streaming usage in a timeframe
	 * The resolution can be "days" or "months"
	 *
	 * @link http://docs.borhan.org/api/partner/usage
	 * @action getUsage
	 * @param int $year
	 * @param int $month
	 * @param BorhanReportInterval $resolution
	 * @return BorhanPartnerUsage
	 * 
	 * @throws APIErrors::UNKNOWN_PARTNER_ID
	 * @deprecated use getStatistics instead
	 */
	public function getUsageAction($year = '', $month = 1, $resolution = "days")
	{
		$dbPartner = PartnerPeer::retrieveByPK($this->getPartnerId());
		
		if(!$dbPartner)
			throw new BorhanAPIException(APIErrors::UNKNOWN_PARTNER_ID, $this->getPartnerId());
		
		$packages = new PartnerPackages();
		$partnerUsage = new BorhanPartnerUsage();
		$partnerPackage = $packages->getPackageDetails($dbPartner->getPartnerPackage());
		
		$report_date = date("Y-m-d", time());
		
		list($totalStorage, $totalUsage, $totalTraffic) = myPartnerUtils::collectPartnerUsageFromDWH($dbPartner, $partnerPackage, $report_date);
		
		$partnerUsage->hostingGB = round($totalStorage / 1024, 2); // from MB to GB
		$totalUsageGB = round($totalUsage / 1024 / 1024, 2); // from KB to GB
		if($partnerPackage)
		{
			$partnerUsage->Percent = round(($totalUsageGB / $partnerPackage['cycle_bw']) * 100, 2);
			$partnerUsage->packageBW = $partnerPackage['cycle_bw'];
		}
		$partnerUsage->usageGB = $totalUsageGB;
		$partnerUsage->reachedLimitDate = $dbPartner->getUsageLimitWarning();
		
		if($year != '')
		{
			$startDate = gmmktime(0, 0, 0, $month, 1, $year);
			$endDate = gmmktime(0, 0, 0, $month, date('t', $startDate), $year);
			
			if($resolution == reportInterval::MONTHS)
			{
				$startDate = gmmktime(0, 0, 0, 1, 1, $year);
				$endDate = gmmktime(0, 0, 0, 12, 31, $year);
				
				if(intval(date('Y')) == $year)
					$endDate = time();
			}
			
			$usageGraph = myPartnerUtils::getPartnerUsageGraph($startDate, $endDate, $dbPartner, $resolution);
			// currently we provide only one line, output as a string.
			// in the future this could be extended to something like BorhanGraphLines object
			$partnerUsage->usageGraph = $usageGraph;
		}
		
		return $partnerUsage;
	}
	
	/**
	 * Get usage statistics for a partner
	 * Calculation is done according to partner's package
	 *
	 * @action getStatistics
	 * @return BorhanPartnerStatistics
	 * 
	 * @throws APIErrors::UNKNOWN_PARTNER_ID
	 */
	public function getStatisticsAction()
	{
		$dbPartner = PartnerPeer::retrieveByPK($this->getPartnerId());
		
		if(!$dbPartner)
			throw new BorhanAPIException(APIErrors::UNKNOWN_PARTNER_ID, $this->getPartnerId());
		
		$packages = new PartnerPackages();
		$partnerUsage = new BorhanPartnerStatistics();
		$partnerPackage = $packages->getPackageDetails($dbPartner->getPartnerPackage());
		
		$report_date = date("Y-m-d", time());
		
		list($totalStorage, $totalUsage, $totalTraffic) = myPartnerUtils::collectPartnerStatisticsFromDWH($dbPartner, $partnerPackage, $report_date);
		
		$partnerUsage->hosting = round($totalStorage / 1024, 2); // from MB to GB
		$totalUsageGB = round($totalUsage / 1024 / 1024, 2); // from KB to GB
		if($partnerPackage)
		{
			$partnerUsage->usagePercent = round(($totalUsageGB / $partnerPackage['cycle_bw']) * 100, 2);
			$partnerUsage->packageBandwidthAndStorage = $partnerPackage['cycle_bw'];
		}
		if($totalTraffic)
		{
			$partnerUsage->bandwidth = round($totalTraffic / 1024 / 1024, 2); // from KB to GB
		}
		$partnerUsage->usage = $totalUsageGB;
		$partnerUsage->reachedLimitDate = $dbPartner->getUsageLimitWarning();
		
		return $partnerUsage;
	}
	
	/**
	 * Retrieve a list of partner objects which the current user is allowed to access.
	 * 
	 * @action listPartnersForUser
	 * @param BorhanPartnerFilter $partnerFilter
	 * @param BorhanFilterPager $pager
	 * @return BorhanPartnerListResponse
	 * @throws BorhanErrors::INVALID_USER_ID
	 * 
	 */
	public function listPartnersForUserAction(BorhanPartnerFilter $partnerFilter = null, BorhanFilterPager $pager = null)
	{	
		$partnerId = kCurrentContext::$master_partner_id;
		
		if (isset(kCurrentContext::$partner_id))
			$partnerId = kCurrentContext::$partner_id;
		
		$c = new Criteria();
		$currentUser = kuserPeer::getKuserByPartnerAndUid($partnerId, kCurrentContext::$ks_uid, true);
		
		if(!$currentUser)
		{
		    $userId = kCurrentContext::$ks_uid;
		    throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
		}
		
		if (!$pager)
		{
		    $pager = new BorhanFilterPager();
		}
		
		$dbFilter = null;
		if ($partnerFilter)
		{
		    $dbFilter = new partnerFilter();
		    $partnerFilter->toObject($dbFilter);
		}	
			
		$allowedIds = $currentUser->getAllowedPartnerIds($dbFilter);
		
		$pager->attachToCriteria($c);
		$partners = array();
		$partners = myPartnerUtils::getPartnersArray($allowedIds, $c);	
		$borhanPartners = BorhanPartnerArray::fromPartnerArray($partners );
		$response = new BorhanPartnerListResponse();
		$response->objects = $borhanPartners;
		$response->totalCount = count($partners);	
		
		return $response;
	}

	/**
	 * List partners by filter with paging support
	 * Current implementation will only list the sub partners of the partner initiating the api call (using the current KS).
	 * This action is only partially implemented to support listing sub partners of a VAR partner.
	 * @action list
	 * @param BorhanPartnerFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanPartnerListResponse
	 */
	public function listAction(BorhanPartnerFilter $filter = null, BorhanFilterPager $pager = null)
	{
	    if (is_null($filter))
	    {
	        $filter = new BorhanPartnerFilter();
	    }
	    
	    if (is_null($pager))
	    {
	        $pager = new BorhanFilterPager();   
	    }
	    
	    $partnerFilter = new partnerFilter();
	    $filter->toObject($partnerFilter);
	    
	    $c = PartnerPeer::getDefaultCriteria();
		
	    $partnerFilter->attachToCriteria($c);
		$response = new BorhanPartnerListResponse();
		$response->totalCount = PartnerPeer::doCount($c);
		
	    $pager->attachToCriteria($c);
	    $dbPartners = PartnerPeer::doSelect($c);
	    
		$partnersArray = BorhanPartnerArray::fromPartnerArray($dbPartners);
		
		$response->objects = $partnersArray;
		return $response;
	}
	
	/**
	 * List partner's current processes' statuses
	 * 
	 * @action listFeatureStatus
	 * @throws APIErrors::UNKNOWN_PARTNER_ID
	 * @return BorhanFeatureStatusListResponse
	 */
	public function listFeatureStatusAction()
	{
		if (is_null($this->getKs()) || is_null($this->getPartner()) || !$this->getPartnerId())
			throw new BorhanAPIException(APIErrors::MISSING_KS);
			
		$dbPartner = $this->getPartner();
		if ( ! $dbPartner )
			throw new BorhanAPIException ( APIErrors::UNKNOWN_PARTNER_ID , $this->getPartnerId() );
		
		$dbFeaturesStatus = $dbPartner->getFeaturesStatus();
		
		$featuresStatus = BorhanFeatureStatusArray::fromDbArray($dbFeaturesStatus, $this->getResponseProfile());
		
		$response = new BorhanFeatureStatusListResponse();
		$response->objects = $featuresStatus;
		$response->totalCount = count($featuresStatus);
		
		return $response;
	}
	
	/**
	 * Count partner's existing sub-publishers (count includes the partner itself).
	 * 
	 * @action count
	 * @param BorhanPartnerFilter $filter
	 * @return int
	 */
    public function countAction (BorhanPartnerFilter $filter = null)
    {
        if (!$filter)
            $filter = new BorhanPartnerFilter();
            
        $dbFilter = new partnerFilter();
        $filter->toObject($dbFilter);
        
        $c = PartnerPeer::getDefaultCriteria();
        $dbFilter->attachToCriteria($c);
        
        return PartnerPeer::doCount($c);
    }
	
}

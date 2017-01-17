<?php
/**
 * Distribution Profile service
 *
 * @service distributionProfile
 * @package plugins.contentDistribution
 * @subpackage api.services
 */
class DistributionProfileService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		$this->applyPartnerFilterForClass('DistributionProfile');
		
		if(!ContentDistributionPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, ContentDistributionPlugin::PLUGIN_NAME);
	}
	
	/**
	 * Add new Distribution Profile
	 * 
	 * @action add
	 * @param BorhanDistributionProfile $distributionProfile
	 * @return BorhanDistributionProfile
	 * @throws ContentDistributionErrors::DISTRIBUTION_PROVIDER_NOT_FOUND
	 */
	function addAction(BorhanDistributionProfile $distributionProfile)
	{
		$distributionProfile->validatePropertyMinLength("name", 1);
		$distributionProfile->validatePropertyNotNull("providerType");
					
		if(is_null($distributionProfile->status))
			$distributionProfile->status = BorhanDistributionProfileStatus::DISABLED;
		
		$providerType = kPluginableEnumsManager::apiToCore('DistributionProviderType', $distributionProfile->providerType);
		$dbDistributionProfile = DistributionProfilePeer::createDistributionProfile($providerType);
		if(!$dbDistributionProfile)
			throw new BorhanAPIException(ContentDistributionErrors::DISTRIBUTION_PROVIDER_NOT_FOUND, $distributionProfile->providerType);
			
		$distributionProfile->toInsertableObject($dbDistributionProfile);
		$dbDistributionProfile->setPartnerId($this->impersonatedPartnerId);
		$dbDistributionProfile->save();
		
		$distributionProfile = BorhanDistributionProfileFactory::createBorhanDistributionProfile($dbDistributionProfile->getProviderType());
		$distributionProfile->fromObject($dbDistributionProfile, $this->getResponseProfile());
		return $distributionProfile;
	}
	
	/**
	 * Get Distribution Profile by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanDistributionProfile
	 * @throws ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbDistributionProfile = DistributionProfilePeer::retrieveByPK($id);
		if (!$dbDistributionProfile)
			throw new BorhanAPIException(ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND, $id);
			
		$distributionProfile = BorhanDistributionProfileFactory::createBorhanDistributionProfile($dbDistributionProfile->getProviderType());
		$distributionProfile->fromObject($dbDistributionProfile, $this->getResponseProfile());
		return $distributionProfile;
	}
	
	/**
	 * Update Distribution Profile by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanDistributionProfile $distributionProfile
	 * @return BorhanDistributionProfile
	 * @throws ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND
	 */
	function updateAction($id, BorhanDistributionProfile $distributionProfile)
	{
		$dbDistributionProfile = DistributionProfilePeer::retrieveByPK($id);
		if (!$dbDistributionProfile)
			throw new BorhanAPIException(ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND, $id);
		
		if ($distributionProfile->name !== null)
			$distributionProfile->validatePropertyMinLength("name", 1);
			
		$distributionProfile->toUpdatableObject($dbDistributionProfile);
		$dbDistributionProfile->save();
		
		$distributionProfile = BorhanDistributionProfileFactory::createBorhanDistributionProfile($dbDistributionProfile->getProviderType());
		$distributionProfile->fromObject($dbDistributionProfile, $this->getResponseProfile());
		return $distributionProfile;
	}
	
	/**
	 * Update Distribution Profile status by id
	 * 
	 * @action updateStatus
	 * @param int $id
	 * @param BorhanDistributionProfileStatus $status
	 * @return BorhanDistributionProfile
	 * @throws ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND
	 */
	function updateStatusAction($id, $status)
	{
		$dbDistributionProfile = DistributionProfilePeer::retrieveByPK($id);
		if (!$dbDistributionProfile)
			throw new BorhanAPIException(ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND, $id);
		
		$dbDistributionProfile->setStatus($status);
		$dbDistributionProfile->save();
		
		$distributionProfile = BorhanDistributionProfileFactory::createBorhanDistributionProfile($dbDistributionProfile->getProviderType());
		$distributionProfile->fromObject($dbDistributionProfile, $this->getResponseProfile());
		return $distributionProfile;
	}
	
	/**
	 * Delete Distribution Profile by id
	 * 
	 * @action delete
	 * @param int $id
	 * @throws ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND
	 */
	function deleteAction($id)
	{
		$dbDistributionProfile = DistributionProfilePeer::retrieveByPK($id);
		if (!$dbDistributionProfile)
			throw new BorhanAPIException(ContentDistributionErrors::DISTRIBUTION_PROFILE_NOT_FOUND, $id);

		$dbDistributionProfile->setStatus(DistributionProfileStatus::DELETED);
		$dbDistributionProfile->save();
	}
	
	
	/**
	 * List all distribution providers
	 * 
	 * @action list
	 * @param BorhanDistributionProfileFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDistributionProfileListResponse
	 */
	function listAction(BorhanDistributionProfileFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDistributionProfileFilter();
			
		if (!$pager)
		    $pager = new BorhanFilterPager();
        
		 //Change the pageSize to support clients who hae had all their dist. profiles listed in Eagle
		$pager->pageSize = 100;
		
		$c = new Criteria();
		$distributionProfileFilter = new DistributionProfileFilter();
		$filter->toObject($distributionProfileFilter);
		
		$distributionProfileFilter->attachToCriteria($c);
		$count = DistributionProfilePeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$list = DistributionProfilePeer::doSelect($c);
		
		$response = new BorhanDistributionProfileListResponse();
		$response->objects = BorhanDistributionProfileArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
	
		return $response;
	}	
	
	/**
	 * @action listByPartner
	 * @param BorhanPartnerFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDistributionProfileListResponse
	 */
	public function listByPartnerAction(BorhanPartnerFilter $filter = null, BorhanFilterPager $pager = null)
	{
		$c = new Criteria();
		
		if (!is_null($filter))
		{
			
			$partnerFilter = new partnerFilter();
			$filter->toObject($partnerFilter);
			$partnerFilter->set('_gt_id', 0);
			
			$partnerCriteria = new Criteria();
			$partnerFilter->attachToCriteria($partnerCriteria);
			$partnerCriteria->setLimit(1000);
			$partnerCriteria->clearSelectColumns();
			$partnerCriteria->addSelectColumn(PartnerPeer::ID);
			$stmt = PartnerPeer::doSelectStmt($partnerCriteria);
			
			if($stmt->rowCount() < 1000) // otherwise, it's probably all partners
			{
				$partnerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
				$c->add(DistributionProfilePeer::PARTNER_ID, $partnerIds, Criteria::IN);
			}
		}
			
		if (is_null($pager))
			$pager = new BorhanFilterPager();
			
		$c->addDescendingOrderByColumn(DistributionProfilePeer::CREATED_AT);
		
		$totalCount = DistributionProfilePeer::doCount($c);
		$pager->attachToCriteria($c);
		$list = DistributionProfilePeer::doSelect($c);
		$newList = BorhanDistributionProfileArray::fromDbArray($list, $this->getResponseProfile());
		
		$response = new BorhanDistributionProfileListResponse();
		$response->totalCount = $totalCount;
		$response->objects = $newList;
		return $response;
	}
}

<?php
/**
 * @abstract
 * @package api
 * @subpackage services
 */
abstract class BorhanBaseService 
{
	/**
	 * @var ks
	 */
	private $ks = null;
	
	/**
	 * @var Partner
	 */
	private $partner = null;

	/**
	 * @var int
	 */
	private $partnerId = null;
	
	/**
	 * @var kuser
	 */
	private $kuser = null;

	/**
	 * @var BorhanPartner
	 */
	private $operating_partner = null;
	
	/**
	 * @var BorhanDetachedResponseProfile
	 */
	private $responseProfile = null;
	 
	
	protected $private_partner_data = null; /// will be used internally and from the actual services for setting the
	
	protected $impersonatedPartnerId = null;
	
	protected $serviceId = null;
	
	protected $serviceName = null;
	
	protected $actionName = null;
	
	protected $partnerGroup = null;
	
	public function __construct()
	{
		//TODO: initialize $this->serviceName here instead of in initService method
	}	

	
	public function __destruct( )
	{
	}
	
	
	/**
	 * Should return true or false for allowing/disallowing borhan network filter for the given action.
	 * Can be extended to partner specific checks etc...
	 * @return true if "borhan network" is enabled for the given action or false otherwise
	 * @param string $actionName action name
	 */
	protected function borhanNetworkAllowed($actionName)
	{
		return false;
	}
	
	/**
	 * Should return 'false' if no partner is required for that action, to make it usable with no KS or partner_id variable.
	 * Return 'true' otherwise (most actions).
	 * @param string $actionName
	 */
	protected function partnerRequired($actionName)
	{
		return true;
	}
	
	/**
	 * Should return 'true' if global partner (partner 0) should be added to the partner group filter for the given action, or 'false' otherwise.
	 * Enter description here ...
	 * @param string $actionName action name
	 */
	protected function globalPartnerAllowed($actionName)
	{
		return false;
	} 
		
	public function setResponseProfile(BorhanDetachedResponseProfile $responseProfile = null)
	{
		$this->responseProfile = $responseProfile;
	}
		
	/**
	 * @return BorhanDetachedResponseProfile
	 */
	protected function getResponseProfile()
	{
		return $this->responseProfile;
	}
	
	public function initService($serviceId, $serviceName, $actionName)
	{	
		// init service and action name
		$this->serviceId = $serviceId;
		$this->serviceName = $serviceName;
		$this->actionName  = $actionName;
		
		// impersonated partner = partner parameter from the request
		$this->impersonatedPartnerId = kCurrentContext::$partner_id;
		
		$this->ks = kCurrentContext::$ks_object ? kCurrentContext::$ks_object : null;
		
		// operating partner = partner from the request or the ks partner
		$partnerId = kCurrentContext::getCurrentPartnerId();
		
		// if there is no session, assume it's partner 0 using actions that doesn't require ks
		if(is_null($partnerId))
			$partnerId = 0;
		
		$this->partnerId = $partnerId;

		// check if current aciton is allowed and if private partner data access is allowed
		$allowPrivatePartnerData = false;
		$actionPermitted = $this->isPermitted($allowPrivatePartnerData);

		// action not permitted at all, not even borhan network
		if (!$actionPermitted)
		{			
			$e = new BorhanAPIException ( APIErrors::SERVICE_FORBIDDEN, $this->serviceId.'->'.$this->actionName); //TODO: should sometimes thorow MISSING_KS instead
			header("X-Borhan:error-".$e->getCode());
			header("X-Borhan-App: exiting on error ".$e->getCode()." - ".$e->getMessage());
			throw $e;		
		}

		$this->validateApiAccessControl();
		
		// init partner filter parameters
		$this->private_partner_data = $allowPrivatePartnerData;
		$this->partnerGroup = kPermissionManager::getPartnerGroup($this->serviceId, $this->actionName);
		if ($this->globalPartnerAllowed($this->actionName)) {
			$this->partnerGroup = PartnerPeer::GLOBAL_PARTNER.','.trim($this->partnerGroup,',');
		}
		
		$this->setPartnerFilters($partnerId);
		
		kCurrentContext::$HTMLPurifierBehaviour = $this->getPartner()->getHtmlPurifierBehaviour();
	}

	/**
	 * apply partner filters according to current context and permissions
	 * 
	 * @param int $partnerId
	 */
	protected function setPartnerFilters($partnerId)
	{
		myPartnerUtils::resetAllFilters();
		myPartnerUtils::applyPartnerFilters($partnerId ,$this->private_partner_data ,$this->partnerGroup() ,$this->borhanNetworkAllowed($this->actionName));
	}
	
/* >--------------------- Security and config settings ----------------------- */

	/**
	 * Check if current action is permitted for current context (ks/partner/user)
	 * @param bool $allowPrivatePartnerData true if access to private partner data is allowed, false otherwise (borhan network)
	 * @throws BorhanErrors::MISSING_KS
	 */
	protected function isPermitted(&$allowPrivatePartnerData)
	{		
		// if no partner defined but required -> error MISSING_KS
		if ($this->partnerRequired($this->actionName) && 
			$this->partnerId != Partner::BATCH_PARTNER_ID && 
			!$this->getPartner())
		{
			throw new BorhanAPIException(BorhanErrors::MISSING_KS);
		}
		
		// check if actions is permitted for current context
		$isActionPermitted = kPermissionManager::isActionPermitted($this->serviceId, $this->actionName);
		
		// if action permitted - no problem to access action and the private partner data
		if ($isActionPermitted) {
			$allowPrivatePartnerData = true; // allow private partner data
			return true; // action permitted with access to partner private data
		}
		BorhanLog::err("Action is not permitted");
		
		// action not permitted for current user - check if borhan network is allowed
		if (!kCurrentContext::$ks && $this->borhanNetworkAllowed($this->actionName))
		{
			// if the service action support borhan network - continue without private data
			$allowPrivatePartnerData = false; // DO NOT allow private partner data
			return true; // action permitted (without private partner data)
		}
		BorhanLog::err("Borhan network is not allowed");
		
		// action not permitted, not even without private partner data access
		return false;
	}
		
		
	/**
	 * Can be used from derived classes to set additionl filter that don't automatically happen in applyPartnerFilters
	 * 
	 * @param string $peer
	 */
	protected function applyPartnerFilterForClass($peer)
	{
		if ( $this->getPartner() )
			$partner_id = $this->getPartner()->getId();
		else
			$partner_id = Partner::PARTNER_THAT_DOWS_NOT_EXIST;
			
		myPartnerUtils::addPartnerToCriteria ( $peer , $partner_id , $this->private_partner_data , $this->partnerGroup($peer) , $this->borhanNetworkAllowed($this->actionName)  );
	}	
	
	
	protected function applyPartnerFilterForClassNoBorhanNetwork ( $peer )
	{
		if ( $this->getPartner() )
			$partner_id = $this->getPartner()->getId();
		else
			$partner_id = -1; 
		myPartnerUtils::addPartnerToCriteria ( $peer , $partner_id , $this->private_partner_data , $this->partnerGroup($peer) , null );
	}
/* <--------------------- Security and config settings ----------------------- */	
	
	/**
	 * @return A comma seperated string of partner ids to which current context is allowed to access
	 */
	protected function partnerGroup($peer = null) 		
	{ 		
		return $this->partnerGroup;
	}
	
	/**
	 * 
	 * @return ks
	 */
	public function getKs()
	{
		return $this->ks;
	}

	public function getPartnerId()
	{
		return $this->partnerId;
	}
	
	/**
	 * @return Partner
	 */
	public function getPartner()
	{
		if (!$this->partner)
			$this->partner = PartnerPeer::retrieveByPK( $this->partnerId );
			
		return $this->partner; 
	}
	
	/**
	 * Returns Kuser (New kuser will be created if it doesn't exists) 
	 *
	 * @return kuser
	 */
	public function getKuser()
	{
		if (!$this->kuser)
		{
			// if no ks, puser id will be null
			if ($this->ks)
				$puserId = $this->ks->user;
			else
				$puserId = null;
				
			$kuser = kuserPeer::createKuserForPartner($this->getPartnerId(), $puserId);
			
			if ($kuser->getStatus() !== BorhanUserStatus::ACTIVE)
				throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
			
			$this->kuser = $kuser;
		}
		
		return $this->kuser;
	}
	
	protected function getKsUniqueString()
	{
		if ($this->ks)
		{
			return $this->ks->getUniqueString();
		}
		else
		{
			return substr ( md5( rand ( 10000,99999 ) . microtime(true) ) , 1 , 7 );
			//throw new Exception ( "Cannot find unique string" );
		}

	}
	
	/**
	 * @param string $file_name
	 * @param string $mime_type
	 * @return kRendererDumpFile
	 */
	protected function dumpFile($filePath, $mimeType)
	{
		return kFileUtils::getDumpFileRenderer($filePath, $mimeType);
	}
	
	/**
	 * @param ISyncableFile $syncable
	 * @param int $fileSubType
	 * @param string $fileName
	 * @param bool $forceProxy
	 * @throws BorhanErrors::FILE_DOESNT_EXIST
	 */
	protected function serveFile(ISyncableFile $syncable, $fileSubType, $fileName, $entryId = null, $forceProxy = false)
	{
		/* @var $fileSync FileSync */
		$syncKey = $syncable->getSyncKey($fileSubType);
		if(!kFileSyncUtils::fileSync_exists($syncKey))
			throw new BorhanAPIException(BorhanErrors::FILE_DOESNT_EXIST);

		list($fileSync, $local) = kFileSyncUtils::getReadyFileSyncForKey($syncKey, true, false);
		if($local)
		{
			$filePath = $fileSync->getFullPath();
			$mimeType = kFile::mimeType($filePath);			
			return $this->dumpFile($filePath, $mimeType);
		}
		else if ( in_array($fileSync->getDc(), kDataCenterMgr::getDcIds()) )
		{
			$remoteUrl = kDataCenterMgr::getRedirectExternalUrl($fileSync);
			BorhanLog::info("Redirecting to [$remoteUrl]");
			if($forceProxy)
			{
				kFileUtils::dumpApiRequest($remoteUrl);
			}
			else
			{
				//TODO find or build function which redurects the API request with all its parameters without using curl.
				// or redirect if no proxy
				header("Location: $remoteUrl");
				die;
			}
		}
		else
		{
			$remoteUrl =  $fileSync->getExternalUrl($entryId);
			header("Location: $remoteUrl");
			die;
		}	
	}

	protected function validateApiAccessControl($partnerId = null)
	{
		// ignore for system partners
		// for cases where an api action has a 'partnerId' parameter which will causes loading that partner instead of the ks partner
		if ($this->getKs() && $this->getKs()->partner_id < 0)
			return;
		
		if (is_null($partnerId))
			$partner = $this->getPartner();
		else
			$partner = PartnerPeer::retrieveByPK($partnerId);
		
		if (!$partner)
			return;
		
		if (!$partner->validateApiAccessControl())
			throw new BorhanAPIException(APIErrors::SERVICE_ACCESS_CONTROL_RESTRICTED, $this->serviceId.'->'.$this->actionName);
	}
}

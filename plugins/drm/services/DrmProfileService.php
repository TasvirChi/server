<?php
/**
 * 
 * @service drmProfile
 * @package plugins.drm
 * @subpackage api.services
 */
class DrmProfileService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('DrmProfile');
		
		if (!DrmPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, DrmPlugin::PLUGIN_NAME);		
	}
	
	/**
	 * Allows you to add a new DrmProfile object
	 * 
	 * @action add
	 * @param BorhanDrmProfile $drmProfile
	 * @return BorhanDrmProfile
	 * 
	 * @throws BorhanErrors::PLUGIN_NOT_AVAILABLE_FOR_PARTNER
	 * @throws BorhanErrors::INVALID_PARTNER_ID
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 * @throws DrmErrors::ACTIVE_PROVIDER_PROFILE_ALREADY_EXIST
	 */
	public function addAction(BorhanDrmProfile $drmProfile)
	{
		// check for required parameters
		$drmProfile->validatePropertyNotNull('name');
		$drmProfile->validatePropertyNotNull('status');
		$drmProfile->validatePropertyNotNull('provider');
		$drmProfile->validatePropertyNotNull('partnerId');
		
		// validate values						
		if (!PartnerPeer::retrieveByPK($drmProfile->partnerId)) {
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $drmProfile->partnerId);
		}
		
		if (!DrmPlugin::isAllowedPartner($drmProfile->partnerId))
		{
			throw new BorhanAPIException(BorhanErrors::PLUGIN_NOT_AVAILABLE_FOR_PARTNER, DrmPlugin::getPluginName(), $drmProfile->partnerId);
		}
		
		$dbDrmProfile = $drmProfile->toInsertableObject();
		
		if(DrmProfilePeer::retrieveByProvider($dbDrmProfile->getProvider()))
		{
			throw new BorhanAPIException(DrmErrors::ACTIVE_PROVIDER_PROFILE_ALREADY_EXIST, $drmProfile->provider);
		}

		// save in database
		
		$dbDrmProfile->save();
		
		// return the saved object
		$drmProfile = BorhanDrmProfile::getInstanceByType($dbDrmProfile->getProvider());
		$drmProfile->fromObject($dbDrmProfile, $this->getResponseProfile());
		return $drmProfile;		
	}
	
	/**
	 * Retrieve a BorhanDrmProfile object by ID
	 * 
	 * @action get
	 * @param int $drmProfileId 
	 * @return BorhanDrmProfile
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function getAction($drmProfileId)
	{
		$dbDrmProfile = DrmProfilePeer::retrieveByPK($drmProfileId);
		
		if (!$dbDrmProfile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmProfileId);
		}
		$drmProfile = BorhanDrmProfile::getInstanceByType($dbDrmProfile->getProvider());
		$drmProfile->fromObject($dbDrmProfile, $this->getResponseProfile());
		
		return $drmProfile;
	}
	

	/**
	 * Update an existing BorhanDrmProfile object
	 * 
	 * @action update
	 * @param int $drmProfileId
	 * @param BorhanDrmProfile $drmProfile
	 * @return BorhanDrmProfile
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	public function updateAction($drmProfileId, BorhanDrmProfile $drmProfile)
	{
		$dbDrmProfile = DrmProfilePeer::retrieveByPK($drmProfileId);
		
		if (!$dbDrmProfile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmProfileId);
		}
								
		$dbDrmProfile = $drmProfile->toUpdatableObject($dbDrmProfile);
		$dbDrmProfile->save();
			
		$drmProfile = BorhanDrmProfile::getInstanceByType($dbDrmProfile->getProvider());
		$drmProfile->fromObject($dbDrmProfile, $this->getResponseProfile());
		
		return $drmProfile;
	}

	/**
	 * Mark the BorhanDrmProfile object as deleted
	 * 
	 * @action delete
	 * @param int $drmProfileId 
	 * @return BorhanDrmProfile
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function deleteAction($drmProfileId)
	{
		$dbDrmProfile = DrmProfilePeer::retrieveByPK($drmProfileId);
		
		if (!$dbDrmProfile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmProfileId);
		}

		$dbDrmProfile->setStatus(DrmProfileStatus::DELETED);
		$dbDrmProfile->save();
			
		$drmProfile = BorhanDrmProfile::getInstanceByType($dbDrmProfile->getProvider());
		$drmProfile->fromObject($dbDrmProfile, $this->getResponseProfile());
		
		return $drmProfile;
	}
	
	/**
	 * List BorhanDrmProfile objects
	 * 
	 * @action list
	 * @param BorhanDrmProfileFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDrmProfileListResponse
	 */
	public function listAction(BorhanDrmProfileFilter  $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDrmProfileFilter();

		$drmProfileFilter = $filter->toObject();
		$c = new Criteria();
		$drmProfileFilter->attachToCriteria($c);
		$count = DrmProfilePeer::doCount($c);
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = DrmProfilePeer::doSelect($c);
		
		$response = new BorhanDrmProfileListResponse();
		$response->objects = BorhanDrmProfileArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
		
		return $response;
	}
	
	/**
	 * Retrieve a BorhanDrmProfile object by provider, if no specific profile defined return default profile
	 * 
	 * @action getByProvider
	 * @param BorhanDrmProviderType $provider
	 * @return BorhanDrmProfile
	 */
	public function getByProviderAction($provider)
	{	
		$drmProfile = BorhanDrmProfile::getInstanceByType($provider);
		$drmProfile->provider = $provider;
		$tmpDbProfile = $drmProfile->toObject();
			
		$dbDrmProfile = DrmProfilePeer::retrieveByProvider($tmpDbProfile->getProvider());
		if(!$dbDrmProfile)
		{
            if ($provider == BorhanDrmProviderType::CENC)
            {
                $dbDrmProfile = new DrmProfile();
            }
            else
            {
                $dbDrmProfile = BorhanPluginManager::loadObject('DrmProfile', $tmpDbProfile->getProvider());
            }
			$dbDrmProfile->setName('default');
			$dbDrmProfile->setProvider($tmpDbProfile->getProvider());
		}		
		$drmProfile->fromObject($dbDrmProfile, $this->getResponseProfile());

		return $drmProfile;
	}
}

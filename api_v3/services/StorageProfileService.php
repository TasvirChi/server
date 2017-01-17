<?php
/**
 * Storage Profiles service
 *
 * @service storageProfile
 * @package api
 * @subpackage services
 */
class StorageProfileService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		$partnerId = $this->getPartnerId();
		if(!$this->getPartner()->getEnabledService(PermissionName::FEATURE_REMOTE_STORAGE))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
			
		$this->applyPartnerFilterForClass('StorageProfile');
	}
	
	/**
	 * Adds a storage profile to the Borhan DB.
	 *
	 * @action add
	 * @param BorhanStorageProfile $storageProfile 
	 * @return BorhanStorageProfile
	 */
	function addAction(BorhanStorageProfile $storageProfile)
	{
		if(!$storageProfile->status)
			$storageProfile->status = BorhanStorageProfileStatus::DISABLED;
			
		$dbStorageProfile = $storageProfile->toInsertableObject();
		/* @var $dbStorageProfile StorageProfile */
		$dbStorageProfile->setPartnerId($this->impersonatedPartnerId);
		$dbStorageProfile->save();
		
		$storageProfile = BorhanStorageProfile::getInstanceByType($dbStorageProfile->getProtocol());
				
		$storageProfile->fromObject($dbStorageProfile, $this->getResponseProfile());
		return $storageProfile;
	}
		
	/**
	 * @action updateStatus
	 * @param int $storageId
	 * @param BorhanStorageProfileStatus $status
	 */
	public function updateStatusAction($storageId, $status)
	{
		$dbStorage = StorageProfilePeer::retrieveByPK($storageId);
		if (!$dbStorage)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $storageId);
			
		$dbStorage->setStatus($status);
		$dbStorage->save();
	}	
	
	/**
	 * Get storage profile by id
	 * 
	 * @action get
	 * @param int $storageProfileId
	 * @return BorhanStorageProfile
	 */
	function getAction($storageProfileId)
	{
		$dbStorageProfile = StorageProfilePeer::retrieveByPK($storageProfileId);
		if (!$dbStorageProfile)
			return null;

		$protocol = $dbStorageProfile->getProtocol();
		$storageProfile = BorhanStorageProfile::getInstanceByType($protocol);
		
		$storageProfile->fromObject($dbStorageProfile, $this->getResponseProfile());
		return $storageProfile;
	}
	
	/**
	 * Update storage profile by id 
	 * 
	 * @action update
	 * @param int $storageProfileId
	 * @param BorhanStorageProfile $storageProfile
	 * @return BorhanStorageProfile
	 */
	function updateAction($storageProfileId, BorhanStorageProfile $storageProfile)
	{
		$dbStorageProfile = StorageProfilePeer::retrieveByPK($storageProfileId);
		if (!$dbStorageProfile)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $storageProfileId);
			
		$dbStorageProfile = $storageProfile->toUpdatableObject($dbStorageProfile);
		$dbStorageProfile->save();
		
		$protocol = $dbStorageProfile->getProtocol();
		$storageProfile = BorhanStorageProfile::getInstanceByType($protocol);
		
		$storageProfile->fromObject($dbStorageProfile, $this->getResponseProfile());
		return $storageProfile;
	}
	
	/**	
	 * @action list
	 * @param BorhanStorageProfileFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanStorageProfileListResponse
	 */
	public function listAction(BorhanStorageProfileFilter $filter = null, BorhanFilterPager $pager = null)
	{
		$c = new Criteria();
		
		if (!$filter)
			$filter = new BorhanStorageProfileFilter();
		
		$storageProfileFilter = new StorageProfileFilter();
		$filter->toObject($storageProfileFilter);
		$storageProfileFilter->attachToCriteria($c);
		$list = StorageProfilePeer::doSelect($c);
			
		if (!$pager)
			$pager = new BorhanFilterPager();
			
		$pager->attachToCriteria($c);
		
		$response = new BorhanStorageProfileListResponse();
		$response->totalCount = StorageProfilePeer::doCount($c);
		$response->objects = BorhanStorageProfileArray::fromDbArray($list, $this->getResponseProfile());
		return $response;
	}
	
}

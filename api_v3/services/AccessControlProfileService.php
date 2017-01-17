<?php

/**
 * Manage access control profiles
 *
 * @service accessControlProfile
 */
class AccessControlProfileService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('accessControl'); 	
	}
	
	/**
	 * Add new access control profile
	 * 
	 * @action add
	 * @param BorhanAccessControlProfile $accessControlProfile
	 * @return BorhanAccessControlProfile
	 */
	function addAction(BorhanAccessControlProfile $accessControlProfile)
	{
		$dbAccessControl = $accessControlProfile->toInsertableObject();
		$dbAccessControl->setPartnerId($this->getPartnerId());
		$dbAccessControl->save();
		
		$accessControlProfile = new BorhanAccessControlProfile();
		$accessControlProfile->fromObject($dbAccessControl, $this->getResponseProfile());
		return $accessControlProfile;
	}
	
	/**
	 * Get access control profile by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanAccessControlProfile
	 * 
	 * @throws BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbAccessControl = accessControlPeer::retrieveByPK($id);
		if (!$dbAccessControl)
			throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND, $id);
			
		$accessControlProfile = new BorhanAccessControlProfile();
		$accessControlProfile->fromObject($dbAccessControl, $this->getResponseProfile());
		return $accessControlProfile;
	}
	
	/**
	 * Update access control profile by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanAccessControlProfile $accessControlProfile
	 * @return BorhanAccessControlProfile
	 * 
	 * @throws BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND
	 */
	function updateAction($id, BorhanAccessControlProfile $accessControlProfile)
	{
		$dbAccessControl = accessControlPeer::retrieveByPK($id);
		if (!$dbAccessControl)
			throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND, $id);
		
		$accessControlProfile->toUpdatableObject($dbAccessControl);
		$dbAccessControl->save();
		
		$accessControlProfile = new BorhanAccessControlProfile();
		$accessControlProfile->fromObject($dbAccessControl, $this->getResponseProfile());
		return $accessControlProfile;
	}
	
	/**
	 * Delete access control profile by id
	 * 
	 * @action delete
	 * @param int $id
	 * 
	 * @throws BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND
	 * @throws BorhanErrors::CANNOT_DELETE_DEFAULT_ACCESS_CONTROL
	 */
	function deleteAction($id)
	{
		$dbAccessControl = accessControlPeer::retrieveByPK($id);
		if (!$dbAccessControl)
			throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND, $id);

		if ($dbAccessControl->getIsDefault())
			throw new BorhanAPIException(BorhanErrors::CANNOT_DELETE_DEFAULT_ACCESS_CONTROL);
			
		$dbAccessControl->setDeletedAt(time());
		try
		{
			$dbAccessControl->save();
		}
		catch(kCoreException $e)
		{
			$code = $e->getCode();
			switch($code)
			{
				case kCoreException::EXCEEDED_MAX_ENTRIES_PER_ACCESS_CONTROL_UPDATE_LIMIT :
					throw new BorhanAPIException(BorhanErrors::EXCEEDED_ENTRIES_PER_ACCESS_CONTROL_FOR_UPDATE, $id);
				case kCoreException::NO_DEFAULT_ACCESS_CONTROL :
					throw new BorhanAPIException(BorhanErrors::CANNOT_TRANSFER_ENTRIES_TO_ANOTHER_ACCESS_CONTROL_OBJECT);
				default:
					throw $e;
			}
		}
	}
	
	/**
	 * List access control profiles by filter and pager
	 * 
	 * @action list
	 * @param BorhanFilterPager $filter
	 * @param BorhanAccessControlProfileFilter $pager
	 * @return BorhanAccessControlProfileListResponse
	 */
	function listAction(BorhanAccessControlProfileFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanAccessControlProfileFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
}
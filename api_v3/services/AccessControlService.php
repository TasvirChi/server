<?php

/**
 * Add & Manage Access Controls
 *
 * @service accessControl
 * @deprecated use accessControlProfile service instead
 */
class AccessControlService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('accessControl'); 	
	}
	
	/**
	 * Add new Access Control Profile
	 * 
	 * @action add
	 * @param BorhanAccessControl $accessControl
	 * @return BorhanAccessControl
	 */
	function addAction(BorhanAccessControl $accessControl)
	{
		$accessControl->validatePropertyMinLength("name", 1);
		$accessControl->partnerId = $this->getPartnerId();
		
		$dbAccessControl = new accessControl();
		$accessControl->toObject($dbAccessControl);
		$dbAccessControl->save();
		
		$accessControl = new BorhanAccessControl();
		$accessControl->fromObject($dbAccessControl, $this->getResponseProfile());
		return $accessControl;
	}
	
	/**
	 * Get Access Control Profile by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanAccessControl
	 */
	function getAction($id)
	{
		$dbAccessControl = accessControlPeer::retrieveByPK($id);
		if (!$dbAccessControl)
			throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND, $id);
			
		$accessControl = new BorhanAccessControl();
		$accessControl->fromObject($dbAccessControl, $this->getResponseProfile());
		return $accessControl;
	}
	
	/**
	 * Update Access Control Profile by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanAccessControl $accessControl
	 * @return BorhanAccessControl
	 * 
	 * @throws BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND
	 * @throws BorhanErrors::ACCESS_CONTROL_NEW_VERSION_UPDATE
	 */
	function updateAction($id, BorhanAccessControl $accessControl)
	{
		$dbAccessControl = accessControlPeer::retrieveByPK($id);
		if (!$dbAccessControl)
			throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_ID_NOT_FOUND, $id);
	
		$rules = $dbAccessControl->getRulesArray();
		foreach($rules as $rule)
		{
			if(!($rule instanceof kAccessControlRestriction))
				throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_NEW_VERSION_UPDATE, $id);
		}
		
		$accessControl->validatePropertyMinLength("name", 1, true);
			
		$accessControl->toUpdatableObject($dbAccessControl);
		$dbAccessControl->save();
		
		$accessControl = new BorhanAccessControl();
		$accessControl->fromObject($dbAccessControl, $this->getResponseProfile());
		return $accessControl;
	}
	
	/**
	 * Delete Access Control Profile by id
	 * 
	 * @action delete
	 * @param int $id
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
	 * List Access Control Profiles by filter and pager
	 * 
	 * @action list
	 * @param BorhanFilterPager $filter
	 * @param BorhanAccessControlFilter $pager
	 * @return BorhanAccessControlListResponse
	 */
	function listAction(BorhanAccessControlFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanAccessControlFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());  
	}
}
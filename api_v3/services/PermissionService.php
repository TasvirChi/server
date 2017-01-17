<?php

/**
 * Permission service lets you create and manage user permissions
 * @service permission
 * @package api
 * @subpackage services
 */
class PermissionService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		self::applyPartnerFilterForClass('Permission');
		self::applyPartnerFilterForClass('PermissionItem');
	}
	
	protected function globalPartnerAllowed($actionName)
	{
		if ($actionName === 'get') {
			return true;
		}
		if ($actionName === 'list') {
			return true;
		}
		return parent::globalPartnerAllowed($actionName);
	}
	
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'getCurrentPermissions') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}

	
	/**
	 * Adds a new permission object to the account.
	 * 
	 * @action add
	 * @param BorhanPermission $permission The new permission
	 * @return BorhanPermission The added permission object
	 * 
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 * @throws BorhanErrors::PROPERTY_VALIDATION_NOT_UPDATABLE
	 */
	public function addAction(BorhanPermission $permission)
	{
		$permission->validatePropertyNotNull('name');
		
		if (strpos($permission->name, ',') !== false) {
			throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'name');
		}

		if (!$permission->friendlyName) {
			$permission->friendlyName = $permission->name;
		}
		
		if (!$permission->status) {
			$permission->status = BorhanPermissionStatus::ACTIVE;
		}
											
		$dbPermission = $permission->toInsertableObject();
		
		$dbPermission->setType(PermissionType::NORMAL);  // only normal permission types are added through this services
		$dbPermission->setPartnerId($this->getPartnerId());
		
		try { PermissionPeer::addToPartner($dbPermission, $this->getPartnerId()); }
		catch (kPermissionException $e) {
			$code = $e->getCode();
			if ($code === kPermissionException::PERMISSION_ALREADY_EXISTS) {
				throw new BorhanAPIException(BorhanErrors::PERMISSION_ALREADY_EXISTS, $dbPermission->getName(), $this->getPartnerId());
			}
			if ($code === kPermissionException::PERMISSION_ITEM_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::PERMISSION_ITEM_NOT_FOUND);
			}			
			throw $e;
		}
		
		$permission = new BorhanPermission();
		$permission->fromObject($dbPermission, $this->getResponseProfile());
		
		return $permission;
	}
	
	/**
	 * Retrieves a permission object using its ID.
	 * 
	 * @action get
	 * @param string $permissionName The name assigned to the permission
	 * @return BorhanPermission The retrieved permission object
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function getAction($permissionName)
	{
		$dbPermission = PermissionPeer::getByNameAndPartner($permissionName, explode(',', $this->partnerGroup()));
		
		if (!$dbPermission) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $permissionName);
		}
			
		$permission = new BorhanPermission();
		$permission->fromObject($dbPermission, $this->getResponseProfile());
		
		return $permission;
	}


	/**
	 * Updates an existing permission object.
	 * 
	 * @action update
	 * @param string $permissionName The name assigned to the permission
	 * @param BorhanPermission $permission The updated permission parameters
	 * @return BorhanPermission The updated permission object
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	public function updateAction($permissionName, BorhanPermission $permission)
	{
		$dbPermission = PermissionPeer::getByNameAndPartner($permissionName, explode(',', $this->partnerGroup()));
		
		if (!$dbPermission) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $permissionName);
		}
		
		// only normal permission types are allowed for updating through this service
		if ($dbPermission->getType() !== PermissionType::NORMAL)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $permissionName);
		}
		
		if ($permission->name && $permission->name != $permissionName)
		{
			if (strpos($permission->name, ',') !== false) {
				throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'name');
			}
			
			$existingPermission = PermissionPeer::getByNameAndPartner($permission->name, array($dbPermission->getPartnerId(), PartnerPeer::GLOBAL_PARTNER));
			if ($existingPermission)
			{
				throw new BorhanAPIException(BorhanErrors::PERMISSION_ALREADY_EXISTS, $permission->name, $this->getPartnerId());
			}
		}
		
		$dbPermission = $permission->toUpdatableObject($dbPermission);
		try
		{
			$dbPermission->save();
		}
		catch (kPermissionException $e)
		{
			$code = $e->getCode();
			if ($code === kPermissionException::PERMISSION_ITEM_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::PERMISSION_ITEM_NOT_FOUND);
			}
		}			
		
		$permission = new BorhanPermission();
		$permission->fromObject($dbPermission, $this->getResponseProfile());
		
		return $permission;
	}

	/**
	 * Deletes an existing permission object.
	 * 
	 * @action delete
	 * @param string $permissionName The name assigned to the permission
	 * @return BorhanPermission The deleted permission object
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function deleteAction($permissionName)
	{
		$dbPermission = PermissionPeer::getByNameAndPartner($permissionName, array($this->partnerGroup()));
		
		if (!$dbPermission) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $permissionName);
		}
		
		$dbPermission->setStatus(BorhanPermissionStatus::DELETED);
		$dbPermission->save();
			
		$permission = new BorhanPermission();
		$permission->fromObject($dbPermission, $this->getResponseProfile());
		
		return $permission;
	}
	
	/**
	 * Lists permission objects that are associated with an account.
	 * Blocked permissions are listed unless you use a filter to exclude them.
	 * Blocked permissions are listed unless you use a filter to exclude them.
	 * 
	 * @action list
	 * @param BorhanPermissionFilter $filter A filter used to exclude specific types of permissions
	 * @param BorhanFilterPager $pager A limit for the number of records to display on a page
	 * @return BorhanPermissionListResponse The list of permission objects
	 */
	public function listAction(BorhanPermissionFilter  $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanPermissionFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
	
	/**
	 * Retrieves a list of permissions that apply to the current KS.
	 * 
	 * @action getCurrentPermissions
	 * 
	 * @return string A comma-separated list of current permission names
	 * 
	 */	
	public function getCurrentPermissions()
	{	
		$permissions = kPermissionManager::getCurrentPermissions();
		$permissions = implode(',', $permissions);
		return $permissions;
	}
	
}

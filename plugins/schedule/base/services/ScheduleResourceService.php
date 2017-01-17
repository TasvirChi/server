<?php

/**
 * The ScheduleResource service enables you to create and manage (update, delete, retrieve, etc.) the resources required for scheduled events (cameras, capture devices, etc.).
 * @service scheduleResource
 * @package plugins.schedule
 * @subpackage api.services
 */
class ScheduleResourceService extends BorhanBaseService
{
	/* (non-PHPdoc)
	 * @see BorhanBaseService::initService()
	 */
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		$this->applyPartnerFilterForClass('ScheduleEvent');
		$this->applyPartnerFilterForClass('ScheduleResource');
		$this->applyPartnerFilterForClass('ScheduleEventResource');
	}
	
	/**
	 * Allows you to add a new BorhanScheduleResource object
	 * 
	 * @action add
	 * @param BorhanScheduleResource $scheduleResource
	 * @return BorhanScheduleResource
	 */
	public function addAction(BorhanScheduleResource $scheduleResource)
	{
		// save in database
		$dbScheduleResource = $scheduleResource->toInsertableObject();
		$dbScheduleResource->save();
		
		// return the saved object
		$scheduleResource = BorhanScheduleResource::getInstance($dbScheduleResource, $this->getResponseProfile());
		$scheduleResource->fromObject($dbScheduleResource, $this->getResponseProfile());
		return $scheduleResource;
	
	}
	
	/**
	 * Retrieve a BorhanScheduleResource object by ID
	 * 
	 * @action get
	 * @param int $scheduleResourceId 
	 * @return BorhanScheduleResource
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	public function getAction($scheduleResourceId)
	{
		$dbScheduleResource = ScheduleResourcePeer::retrieveByPK($scheduleResourceId);
		if(!$dbScheduleResource)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $scheduleResourceId);
		}
		
		$scheduleResource = BorhanScheduleResource::getInstance($dbScheduleResource, $this->getResponseProfile());
		$scheduleResource->fromObject($dbScheduleResource, $this->getResponseProfile());
		
		return $scheduleResource;
	}
	
	/**
	 * Update an existing BorhanScheduleResource object
	 * 
	 * @action update
	 * @param int $scheduleResourceId
	 * @param BorhanScheduleResource $scheduleResource
	 * @return BorhanScheduleResource
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	public function updateAction($scheduleResourceId, BorhanScheduleResource $scheduleResource)
	{
		$dbScheduleResource = ScheduleResourcePeer::retrieveByPK($scheduleResourceId);
		if(!$dbScheduleResource)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $scheduleResourceId);
		}
		
		$dbScheduleResource = $scheduleResource->toUpdatableObject($dbScheduleResource);
		$dbScheduleResource->save();
		
		$scheduleResource = BorhanScheduleResource::getInstance($dbScheduleResource, $this->getResponseProfile());
		$scheduleResource->fromObject($dbScheduleResource, $this->getResponseProfile());
		
		return $scheduleResource;
	}
	
	/**
	 * Mark the BorhanScheduleResource object as deleted
	 * 
	 * @action delete
	 * @param int $scheduleResourceId 
	 * @return BorhanScheduleResource
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	public function deleteAction($scheduleResourceId)
	{
		$dbScheduleResource = ScheduleResourcePeer::retrieveByPK($scheduleResourceId);
		if(!$dbScheduleResource)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $scheduleResourceId);
		}
		
		$dbScheduleResource->setStatus(ScheduleResourceStatus::DELETED);
		$dbScheduleResource->save();
		
		$scheduleResource = BorhanScheduleResource::getInstance($dbScheduleResource, $this->getResponseProfile());
		$scheduleResource->fromObject($dbScheduleResource, $this->getResponseProfile());
		
		return $scheduleResource;
	}
	
	/**
	 * List BorhanScheduleResource objects
	 * 
	 * @action list
	 * @param BorhanScheduleResourceFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanScheduleResourceListResponse
	 */
	public function listAction(BorhanScheduleResourceFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanScheduleResourceFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
}

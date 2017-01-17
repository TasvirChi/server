<?php

/**
 * The ScheduleEventResource service enables you create and manage (update, delete, retrieve, etc.) the connections between recording events and the resources required for these events (cameras, capture devices, etc.).
 * @service scheduleEventResource
 * @package plugins.schedule
 * @subpackage api.services
 */
class ScheduleEventResourceService extends BorhanBaseService
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
	 * Allows you to add a new BorhanScheduleEventResource object
	 * 
	 * @action add
	 * @param BorhanScheduleEventResource $scheduleEventResource
	 * @return BorhanScheduleEventResource
	 */
	public function addAction(BorhanScheduleEventResource $scheduleEventResource)
	{
		// save in database
		$dbScheduleEventResource = $scheduleEventResource->toInsertableObject();
		$dbScheduleEventResource->save();
		
		// return the saved object
		$scheduleEventResource = new BorhanScheduleEventResource();
		$scheduleEventResource->fromObject($dbScheduleEventResource, $this->getResponseProfile());
		return $scheduleEventResource;
	
	}
	
	/**
	 * Retrieve a BorhanScheduleEventResource object by ID
	 * 
	 * @action get
	 * @param int $scheduleEventId
	 * @param int $scheduleResourceId 
	 * @return BorhanScheduleEventResource
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	public function getAction($scheduleEventId, $scheduleResourceId)
	{
		$dbScheduleEventResource = ScheduleEventResourcePeer::retrieveByEventAndResource($scheduleEventId, $scheduleResourceId);
		if(!$dbScheduleEventResource)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, "$scheduleEventId,$scheduleResourceId");
		}
		
		$scheduleEventResource = new BorhanScheduleEventResource();
		$scheduleEventResource->fromObject($dbScheduleEventResource, $this->getResponseProfile());
		
		return $scheduleEventResource;
	}
	
	/**
	 * Update an existing BorhanScheduleEventResource object
	 * 
	 * @action update
	 * @param int $scheduleEventId
	 * @param int $scheduleResourceId 
	 * @param BorhanScheduleEventResource $scheduleEventResource
	 * @return BorhanScheduleEventResource
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	public function updateAction($scheduleEventId, $scheduleResourceId, BorhanScheduleEventResource $scheduleEventResource)
	{
		$dbScheduleEventResource = ScheduleEventResourcePeer::retrieveByEventAndResource($scheduleEventId, $scheduleResourceId);
		if(!$dbScheduleEventResource)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, "$scheduleEventId,$scheduleResourceId");
		}
		
		$dbScheduleEventResource = $scheduleEventResource->toUpdatableObject($dbScheduleEventResource);
		$dbScheduleEventResource->save();
		
		$scheduleEventResource = new BorhanScheduleEventResource();
		$scheduleEventResource->fromObject($dbScheduleEventResource, $this->getResponseProfile());
		
		return $scheduleEventResource;
	}
	
	/**
	 * Mark the BorhanScheduleEventResource object as deleted
	 * 
	 * @action delete
	 * @param int $scheduleEventId
	 * @param int $scheduleResourceId 
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	public function deleteAction($scheduleEventId, $scheduleResourceId)
	{
		$dbScheduleEventResource = ScheduleEventResourcePeer::retrieveByEventAndResource($scheduleEventId, $scheduleResourceId);
		if(!$dbScheduleEventResource)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, "$scheduleEventId,$scheduleResourceId");
		}
		
		$dbScheduleEventResource->delete();
	}
	
	/**
	 * List BorhanScheduleEventResource objects
	 * 
	 * @action list
	 * @param BorhanScheduleEventResourceFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanScheduleEventResourceListResponse
	 */
	public function listAction(BorhanScheduleEventResourceFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanScheduleEventResourceFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
}

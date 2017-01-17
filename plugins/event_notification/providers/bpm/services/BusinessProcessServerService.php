<?php
/**
 * Business-Process server service lets you create and manage servers
 * @service businessProcessServer
 * @package plugins.businessProcessNotification
 * @subpackage api.services
 */
class BusinessProcessServerService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		$partnerId = $this->getPartnerId();
		if (!EventNotificationPlugin::isAllowedPartner($partnerId))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, EventNotificationPlugin::PLUGIN_NAME);
			
		$this->applyPartnerFilterForClass('BusinessProcessServer');
	}

	protected function partnerGroup($peer = null) 		
	{ 		
		if($this->actionName == 'list' || $this->actionName == 'get')
		{
			return "0,$this->partnerGroup";
		}
		
		return $this->partnerGroup;
	}
	
	/**
	 * Allows you to add a new Business-Process server object
	 * 
	 * @action add
	 * @param BorhanBusinessProcessServer $businessProcessServer
	 * @return BorhanBusinessProcessServer
	 */
	public function addAction(BorhanBusinessProcessServer $businessProcessServer)
	{
		$dbBusinessProcessServer = $businessProcessServer->toInsertableObject();
		/* @var $dbBusinessProcessServer BusinessProcessServer */
		$dbBusinessProcessServer->setStatus(BusinessProcessServerStatus::ENABLED);
		$dbBusinessProcessServer->setPartnerId($this->impersonatedPartnerId);
		$dbBusinessProcessServer->save();
		
		// return the saved object
		$businessProcessServer = BorhanBusinessProcessServer::getInstanceByType($dbBusinessProcessServer->getType());
		$businessProcessServer->fromObject($dbBusinessProcessServer);
		return $businessProcessServer;
		
	}
	
	/**
	 * Retrieve an Business-Process server object by id
	 * 
	 * @action get
	 * @param int $id 
	 * @return BorhanBusinessProcessServer
	 * 
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */		
	public function getAction($id)
	{
		// get the object
		$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($id);
		if (!$dbBusinessProcessServer)
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND, $id);
			
		// return the found object
		$businessProcessServer = BorhanBusinessProcessServer::getInstanceByType($dbBusinessProcessServer->getType());
		$businessProcessServer->fromObject($dbBusinessProcessServer);
		return $businessProcessServer;
	}
	

	/**
	 * Update an existing Business-Process server object
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanBusinessProcessServer $businessProcessServer
	 * @return BorhanBusinessProcessServer
	 *
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */	
	public function updateAction($id, BorhanBusinessProcessServer $businessProcessServer)
	{
		// get the object
		$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($id);
		if (!$dbBusinessProcessServer)
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND, $id);
		
		// save the object
		$dbBusinessProcessServer = $businessProcessServer->toUpdatableObject($dbBusinessProcessServer);
		$dbBusinessProcessServer->save();
	
		// return the saved object
		$businessProcessServer = BorhanBusinessProcessServer::getInstanceByType($dbBusinessProcessServer->getType());
		$businessProcessServer->fromObject($dbBusinessProcessServer);
		return $businessProcessServer;
	}

	/**
	 * Update Business-Process server status by id
	 * 
	 * @action updateStatus
	 * @param int $id
	 * @param BorhanBusinessProcessServerStatus $status
	 * @return BorhanBusinessProcessServer
	 * 
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */
	function updateStatusAction($id, $status)
	{
		// get the object
		$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($id);
		if (!$dbBusinessProcessServer)
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND, $id);

		if($status == BusinessProcessServerStatus::ACTIVE)
		{
			//Check uniqueness of new object's system name
			$systemNameServers = BusinessProcessServerPeer::retrieveBySystemName($dbBusinessProcessServer->getSystemName());
			if (count($systemNameServers))
				throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_DUPLICATE_SYSTEM_NAME, $dbBusinessProcessServer->getSystemName());
		}	
		
		// save the object
		$dbBusinessProcessServer->setStatus($status);
		$dbBusinessProcessServer->save();
	
		// return the saved object
		$businessProcessServer = BorhanBusinessProcessServer::getInstanceByType($dbBusinessProcessServer->getType());
		$businessProcessServer->fromObject($dbBusinessProcessServer);
		return $businessProcessServer;
	}

	/**
	 * Delete an Business-Process server object
	 * 
	 * @action delete
	 * @param int $id 
	 *
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */		
	public function deleteAction($id)
	{
		// get the object
		$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($id);
		if (!$dbBusinessProcessServer)
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND, $id);

		// set the object status to deleted
		$dbBusinessProcessServer->setStatus(BusinessProcessServerStatus::DELETED);
		$dbBusinessProcessServer->save();
	}
	
	/**
	 * list Business-Process server objects
	 * 
	 * @action list
	 * @param BorhanBusinessProcessServerFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanBusinessProcessServerListResponse
	 */
	public function listAction(BorhanBusinessProcessServerFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanBusinessProcessServerFilter();
			
		if (!$pager)
			$pager = new BorhanFilterPager ();

		$businessProcessServerFilter = new BusinessProcessServerFilter();
		$filter->toObject($businessProcessServerFilter);

		$c = new Criteria();
		$businessProcessServerFilter->attachToCriteria($c);
		$count = BusinessProcessServerPeer::doCount($c);
		
		$pager->attachToCriteria ( $c );
		$list = BusinessProcessServerPeer::doSelect($c);
		
		$response = new BorhanBusinessProcessServerListResponse();
		$response->objects = BorhanBusinessProcessServerArray::fromDbArray($list);
		$response->totalCount = $count;
		
		return $response;
	}
}

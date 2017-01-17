<?php
/**
 * Server Node service
 *
 * @service serverNode
 * @package api
 * @subpackage services
 */
class ServerNodeService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		$partnerId = $this->getPartnerId();
		if(!$this->getPartner()->getEnabledService(PermissionName::FEATURE_SERVER_NODE))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
			
		$this->applyPartnerFilterForClass('serverNode');
	}
	
	/**
	 * Adds a server node to the Borhan DB.
	 *
	 * @action add
	 * @param BorhanServerNode $serverNode
	 * @return BorhanServerNode
	 */
	function addAction(BorhanServerNode $serverNode)
	{	
		$dbServerNode = $this->addNewServerNode($serverNode);
		
		$serverNode = BorhanServerNode::getInstance($dbServerNode, $this->getResponseProfile());
		return $serverNode;
	}
	
	/**
	 * Get server node by id
	 * 
	 * @action get
	 * @param int $serverNodeId
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 * @return BorhanServerNode
	 */
	function getAction($serverNodeId)
	{
		$dbServerNode = ServerNodePeer::retrieveByPK($serverNodeId);
		if (!$dbServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $serverNodeId);
		
		$serverNode = BorhanServerNode::getInstance($dbServerNode, $this->getResponseProfile());
		return $serverNode;
	}
	
	/**
	 * Update server node by id 
	 * 
	 * @action update
	 * @param int $serverNodeId
	 * @param BorhanServerNode $serverNode
	 * @return BorhanServerNode
	 */
	function updateAction($serverNodeId, BorhanServerNode $serverNode)
	{
		$dbServerNode = ServerNodePeer::retrieveByPK($serverNodeId);
		if (!$dbServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $serverNodeId);
			
		$dbServerNode = $serverNode->toUpdatableObject($dbServerNode);
		$dbServerNode->save();
		
		$serverNode = BorhanServerNode::getInstance($dbServerNode, $this->getResponseProfile());
		return $serverNode;
	}
	
	/**
	 * delete server node by id
	 *
	 * @action delete
	 * @param string $serverNodeId
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */
	function deleteAction($serverNodeId)
	{
		$dbServerNode = ServerNodePeer::retrieveByPK($serverNodeId);
		if(!$dbServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $serverNodeId);
	
		$dbServerNode->setStatus(ServerNodeStatus::DELETED);
		$dbServerNode->save();
	}
	
	/**
	 * Disable server node by id
	 *
	 * @action disable
	 * @param string $serverNodeId
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 * @return BorhanServerNode
	 */
	function disableAction($serverNodeId)
	{
		$dbServerNode = ServerNodePeer::retrieveByPK($serverNodeId);
		if(!$dbServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $serverNodeId);
	
		$dbServerNode->setStatus(ServerNodeStatus::DISABLED);
		$dbServerNode->save();
		
		$serverNode = BorhanServerNode::getInstance($dbServerNode, $this->getResponseProfile());
		return $serverNode;
	}
	
	/**
	 * Enable server node by id
	 *
	 * @action enable
	 * @param string $serverNodeId
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 * @return BorhanServerNode
	 */
	function enableAction($serverNodeId)
	{
		$dbServerNode = ServerNodePeer::retrieveByPK($serverNodeId);
		if(!$dbServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $serverNodeId);
	
		$dbServerNode->setStatus(ServerNodeStatus::ACTIVE);
		$dbServerNode->save();
		
		$serverNode = BorhanServerNode::getInstance($dbServerNode, $this->getResponseProfile());
		return $serverNode;
	}
	
	/**	
	 * @action list
	 * @param BorhanServerNodeFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanServerNodeListResponse
	 */
	public function listAction(BorhanServerNodeFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if(!$filter)
			$filter = new BorhanServerNodeFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
		
		return $filter->getTypeListResponse($pager, $this->getResponseProfile(), null);
	}
	
	/**
	 * Update server node status
	 *
	 * @action reportStatus
	 * @param string $hostName
	 * @return BorhanServerNode
	 */
	function reportStatusAction($hostName, BorhanServerNode $serverNode = null)
	{
		$dbServerNode = ServerNodePeer::retrieveActiveServerNode($hostName, $this->getPartnerId());
		
		//Allow serverNodes auto registration without calling add
		if (!$dbServerNode)
		{
			if($serverNode)
			{
				$dbServerNode = $this->addNewServerNode($serverNode);
			}
			else 
				throw new BorhanAPIException(BorhanErrors::SERVER_NODE_NOT_FOUND, $hostName);
		}
	
		$dbServerNode->setHeartbeatTime(time());
		$dbServerNode->save();
	
		$serverNode = BorhanServerNode::getInstance($dbServerNode, $this->getResponseProfile());
		return $serverNode;
	}
	
	private function addNewServerNode(BorhanServerNode $serverNode)
	{
		$dbServerNode = $serverNode->toInsertableObject();
		/* @var $dbServerNode ServerNode */
		$dbServerNode->setPartnerId($this->getPartnerId());
		$dbServerNode->setStatus(ServerNodeStatus::DISABLED);
		$dbServerNode->save();
		
		return $dbServerNode;
	}
}

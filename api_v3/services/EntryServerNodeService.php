<?php
/**
 * Base class for entry server node
 *
 * @service entryServerNode
 * @package api
 * @subpackage services
 */
class EntryServerNodeService extends BorhanBaseService
{

	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass("entry");
		$this->applyPartnerFilterForClass("entryServerNode");
	}

	/**
	 * Adds a entry_user_node to the Borhan DB.
	 *
	 * @action add
	 * @param BorhanEntryServerNode $entryServerNode
	 * @return BorhanEntryServerNode
	 */
	private function addAction(BorhanEntryServerNode $entryServerNode)
	{
		$dbEntryServerNode = $this->addNewEntryServerNode($entryServerNode);

		$te = new TrackEntry();
		$te->setEntryId($dbEntryServerNode->getEntryId());
		$te->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$te->setDescription(__METHOD__ . ":" . __LINE__ . "::" . $dbEntryServerNode->getServerType().":".$dbEntryServerNode->getServerNodeId());
		TrackEntry::addTrackEntry($te);

		$entryServerNode = BorhanEntryServerNode::getInstance($dbEntryServerNode, $this->getResponseProfile());
		return $entryServerNode;

	}

	private function addNewEntryServerNode(BorhanEntryServerNode $entryServerNode)
	{
		$dbEntryServerNode = $entryServerNode->toInsertableObject();
		/* @var $dbEntryServerNode EntryServerNode */
		$dbEntryServerNode->setPartnerId($this->getPartnerId());
		$dbEntryServerNode->setStatus(EntryServerNodeStatus::STOPPED);
		$dbEntryServerNode->save();

		return $dbEntryServerNode;
	}

	/**
	 *
	 * @action update
	 * @param int $id
	 * @param BorhanEntryServerNode $entryServerNode
	 * @return BorhanEntryServerNode|null|object
	 * @throws BorhanAPIException
	 */
	public function updateAction($id, BorhanEntryServerNode $entryServerNode)
	{
		$dbEntryServerNode = EntryServerNodePeer::retrieveByPK($id);
		if (!$dbEntryServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);

		$dbEntryServerNode = $entryServerNode->toUpdatableObject($dbEntryServerNode);
		$dbEntryServerNode->save();

		$entryServerNode = BorhanEntryServerNode::getInstance($dbEntryServerNode, $this->getResponseProfile());
		return $entryServerNode;
	}

	/**
	 * Deletes the row in the database
	 * @action delete
	 * @param int $id
	 * @throws BorhanAPIException
	 */
	private function deleteAction($id)
	{
		$dbEntryServerNode = EntryServerNodePeer::retrieveByPK($id);
		if (!$dbEntryServerNode)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
		$dbEntryServerNode->deleteOrMarkForDeletion();

	}

	/**
	 * @action list
	 * @param BorhanEntryServerNodeFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanEntryServerNodeListResponse
	 */
	public function listAction(BorhanEntryServerNodeFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanEntryServerNodeFilter();
		if (!$pager)
			$pager = new BorhanFilterPager();

		return $filter->getListResponse($pager, $this->getResponseProfile());
	}

	/**
	 * @action get
	 * @param string $id
	 * @return BorhanEntryServerNode
	 * @throws BorhanAPIException
	 */
	public function getAction($id)
	{
		$dbEntryServerNode = EntryServerNodePeer::retrieveByPK( $id );
		if(!$dbEntryServerNode)
			throw new BorhanAPIException(BorhanErrors::ENTRY_SERVER_NODE_NOT_FOUND, $id);

		$entryServerNode = BorhanEntryServerNode::getInstance($dbEntryServerNode);
		if (!$entryServerNode)
			return null;
		$entryServerNode->fromObject($dbEntryServerNode);
		return $entryServerNode;
	}
	
	/**
	 * Validates server node still registered on entry
	 *
	 * @action validateRegisteredEntryServerNode
	 * @param int $id entry server node id
	 *
	 * @throws BorhanAPIException
	 */
	public function validateRegisteredEntryServerNodeAction($id)
	{
		BorhanResponseCacher::disableCache();
		
		$dbEntryServerNode = EntryServerNodePeer::retrieveByPK( $id );
		if(!$dbEntryServerNode)
			throw new BorhanAPIException(BorhanErrors::ENTRY_SERVER_NODE_NOT_FOUND, $id);
		
		/* @var EntryServerNode $dbEntryServerNode */
		$dbEntryServerNode->validateEntryServerNode();
	}
}
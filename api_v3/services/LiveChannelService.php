<?php

/**
 * Live Channel service lets you manage live channels
 *
 * @service liveChannel
 * @package api
 * @subpackage services
 */
class LiveChannelService extends BorhanLiveEntryService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		if($this->getPartnerId() > 0 && !PermissionPeer::isValidForPartner(PermissionName::FEATURE_LIVE_CHANNEL, $this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
	}
	
	/**
	 * Adds new live channel.
	 * 
	 * @action add
	 * @param BorhanLiveChannel $liveChannel Live channel metadata  
	 * @return BorhanLiveChannel The new live channel
	 */
	function addAction(BorhanLiveChannel $liveChannel)
	{
		$dbEntry = $this->prepareEntryForInsert($liveChannel);
		$dbEntry->save();
		
		$te = new TrackEntry();
		$te->setEntryId($dbEntry->getId());
		$te->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$te->setDescription(__METHOD__ . ":" . __LINE__ . "::LIVE_CHANNEL");
		TrackEntry::addTrackEntry($te);
		
		$liveChannel = new BorhanLiveChannel();
		$liveChannel->fromObject($dbEntry, $this->getResponseProfile());
		return $liveChannel;
	}

	
	/**
	 * Get live channel by ID.
	 * 
	 * @action get
	 * @param string $id Live channel id
	 * @return BorhanLiveChannel The requested live channel
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		return $this->getEntry($id, -1, BorhanEntryType::LIVE_CHANNEL);
	}

	
	/**
	 * Update live channel. Only the properties that were set will be updated.
	 * 
	 * @action update
	 * @param string $id Live channel id to update
	 * @param BorhanLiveChannel $liveChannel Live channel metadata to update
	 * @return BorhanLiveChannel The updated live channel
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry id edit
	 */
	function updateAction($id, BorhanLiveChannel $liveChannel)
	{
		return $this->updateEntry($id, $liveChannel, BorhanEntryType::LIVE_CHANNEL);
	}

	/**
	 * Delete a live channel.
	 *
	 * @action delete
	 * @param string $id Live channel id to delete
	 * 
 	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
 	 * @validateUser entry id edit
	 */
	function deleteAction($id)
	{
		$this->deleteEntry($id, BorhanEntryType::LIVE_CHANNEL);
	}
	
	/**
	 * List live channels by filter with paging support.
	 * 
	 * @action list
     * @param BorhanLiveChannelFilter $filter live channel filter
	 * @param BorhanFilterPager $pager Pager
	 * @return BorhanLiveChannelListResponse Wrapper for array of live channels and total count
	 */
	function listAction(BorhanLiveChannelFilter $filter = null, BorhanFilterPager $pager = null)
	{
	    if (!$filter)
			$filter = new BorhanLiveChannelFilter();
			
	    $filter->typeEqual = BorhanEntryType::LIVE_CHANNEL;
	    list($list, $totalCount) = parent::listEntriesByFilter($filter, $pager);
	    
	    $newList = BorhanLiveChannelArray::fromDbArray($list, $this->getResponseProfile());
		$response = new BorhanLiveChannelListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		return $response;
	}
	
	/**
	 * Delivering the status of a live channel (on-air/offline)
	 * 
	 * @action isLive
	 * @param string $id ID of the live channel
	 * @return bool
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	public function isLiveAction ($id)
	{
		$dbEntry = entryPeer::retrieveByPK($id);

		if (!$dbEntry || $dbEntry->getType() != BorhanEntryType::LIVE_CHANNEL)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $id);

		return $dbEntry->hasMediaServer();
	}
}
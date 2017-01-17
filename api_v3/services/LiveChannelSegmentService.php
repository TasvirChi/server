<?php

/**
 * Manage live channel segments
 *
 * @service liveChannelSegment
 */
class LiveChannelSegmentService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('LiveChannelSegment'); 	
		
		if(!PermissionPeer::isValidForPartner(PermissionName::FEATURE_LIVE_CHANNEL, $this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
	}
	
	/**
	 * Add new live channel segment
	 * 
	 * @action add
	 * @param BorhanLiveChannelSegment $liveChannelSegment
	 * @return BorhanLiveChannelSegment
	 */
	function addAction(BorhanLiveChannelSegment $liveChannelSegment)
	{
		$dbLiveChannelSegment = $liveChannelSegment->toInsertableObject();
		$dbLiveChannelSegment->setPartnerId($this->getPartnerId());
		$dbLiveChannelSegment->setStatus(LiveChannelSegmentStatus::ACTIVE);
		$dbLiveChannelSegment->save();
		
		$liveChannelSegment = new BorhanLiveChannelSegment();
		$liveChannelSegment->fromObject($dbLiveChannelSegment, $this->getResponseProfile());
		return $liveChannelSegment;
	}
	
	/**
	 * Get live channel segment by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanLiveChannelSegment
	 * 
	 * @throws BorhanErrors::LIVE_CHANNEL_SEGMENT_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbLiveChannelSegment = LiveChannelSegmentPeer::retrieveByPK($id);
		if (!$dbLiveChannelSegment)
			throw new BorhanAPIException(BorhanErrors::LIVE_CHANNEL_SEGMENT_ID_NOT_FOUND, $id);
			
		$liveChannelSegment = new BorhanLiveChannelSegment();
		$liveChannelSegment->fromObject($dbLiveChannelSegment, $this->getResponseProfile());
		return $liveChannelSegment;
	}
	
	/**
	 * Update live channel segment by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanLiveChannelSegment $liveChannelSegment
	 * @return BorhanLiveChannelSegment
	 * 
	 * @throws BorhanErrors::LIVE_CHANNEL_SEGMENT_ID_NOT_FOUND
	 */
	function updateAction($id, BorhanLiveChannelSegment $liveChannelSegment)
	{
		$dbLiveChannelSegment = LiveChannelSegmentPeer::retrieveByPK($id);
		if (!$dbLiveChannelSegment)
			throw new BorhanAPIException(BorhanErrors::LIVE_CHANNEL_SEGMENT_ID_NOT_FOUND, $id);
		
		$liveChannelSegment->toUpdatableObject($dbLiveChannelSegment);
		$dbLiveChannelSegment->save();
		
		$liveChannelSegment = new BorhanLiveChannelSegment();
		$liveChannelSegment->fromObject($dbLiveChannelSegment, $this->getResponseProfile());
		return $liveChannelSegment;
	}
	
	/**
	 * Delete live channel segment by id
	 * 
	 * @action delete
	 * @param int $id
	 * 
	 * @throws BorhanErrors::LIVE_CHANNEL_SEGMENT_ID_NOT_FOUND
	 */
	function deleteAction($id)
	{
		$dbLiveChannelSegment = LiveChannelSegmentPeer::retrieveByPK($id);
		if (!$dbLiveChannelSegment)
			throw new BorhanAPIException(BorhanErrors::LIVE_CHANNEL_SEGMENT_ID_NOT_FOUND, $id);

		$dbLiveChannelSegment->setStatus(LiveChannelSegmentStatus::DELETED);
		$dbLiveChannelSegment->save();
	}
	
	/**
	 * List live channel segments by filter and pager
	 * 
	 * @action list
	 * @param BorhanFilterPager $filter
	 * @param BorhanLiveChannelSegmentFilter $pager
	 * @return BorhanLiveChannelSegmentListResponse
	 */
	function listAction(BorhanLiveChannelSegmentFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanLiveChannelSegmentFilter();
			
		if (!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
}
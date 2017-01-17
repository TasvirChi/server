<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanLiveChannelSegmentFilter extends BorhanLiveChannelSegmentBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new LiveChannelSegmentFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$liveChannelSegmentFilter = $this->toObject();

		$c = new Criteria();
		$liveChannelSegmentFilter->attachToCriteria($c);
		
		$totalCount = LiveChannelSegmentPeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = LiveChannelSegmentPeer::doSelect($c);
		
		$list = BorhanLiveChannelSegmentArray::fromDbArray($dbList, $responseProfile);
		$response = new BorhanLiveChannelSegmentListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;    
	}
}

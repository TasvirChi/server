<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanLiveChannelFilter extends BorhanLiveChannelBaseFilter
{
	public function __construct()
	{
		$this->typeIn = BorhanEntryType::LIVE_CHANNEL;
	}

	/* (non-PHPdoc)
	 * @see BorhanBaseEntryFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager);
		
	    $newList = BorhanLiveChannelArray::fromDbArray($list, $responseProfile);
		$response = new BorhanLiveChannelListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}

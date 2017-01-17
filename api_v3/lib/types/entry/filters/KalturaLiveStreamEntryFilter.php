<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanLiveStreamEntryFilter extends BorhanLiveStreamEntryBaseFilter
{
	public function __construct()
	{
		$this->typeIn = BorhanEntryType::LIVE_STREAM;
	}

	/* (non-PHPdoc)
	 * @see BorhanBaseEntryFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager);
		
	    $newList = BorhanLiveStreamEntryArray::fromDbArray($list, $responseProfile);
		$response = new BorhanBaseEntryListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}

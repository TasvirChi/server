<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanPlaylistFilter extends BorhanPlaylistBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanBaseEntryFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager);
		
	    $newList = BorhanPlaylistArray::fromDbArray($list, $responseProfile);
		$response = new BorhanPlaylistListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}

<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanMixEntryFilter extends BorhanMixEntryBaseFilter
{
	public function __construct()
	{
		$this->typeIn = BorhanEntryType::MIX;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseEntryFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager);
		
	    $newList = BorhanMixEntryArray::fromDbArray($list, $responseProfile);
		$response = new BorhanBaseEntryListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}

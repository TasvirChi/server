<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanThumbParamsFilter extends BorhanThumbParamsBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanAssetParamsFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);
		
		$response = new BorhanThumbParamsListResponse();
		$response->objects = BorhanThumbParamsArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}
}

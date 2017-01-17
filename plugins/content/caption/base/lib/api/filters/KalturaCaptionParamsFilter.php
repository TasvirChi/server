<?php
/**
 * @package plugins.caption
 * @subpackage api.filters
 */
class BorhanCaptionParamsFilter extends BorhanCaptionParamsBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanAssetParamsFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);
		
		$response = new BorhanCaptionParamsListResponse();
		$response->objects = BorhanCaptionParamsArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}
}

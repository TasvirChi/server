<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanThumbParamsOutputFilter extends BorhanThumbParamsOutputBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new assetParamsOutputFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanAssetParamsFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);
		
		$response = new BorhanThumbParamsOutputListResponse();
		$response->objects = BorhanThumbParamsOutputArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}
}

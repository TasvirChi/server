<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanConversionProfileFilter extends BorhanConversionProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new conversionProfile2Filter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$conversionProfile2Filter = $this->toObject();

		$c = new Criteria();
		$conversionProfile2Filter->attachToCriteria($c);
		
		$totalCount = conversionProfile2Peer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = conversionProfile2Peer::doSelect($c);
		
		$list = BorhanConversionProfileArray::fromDbArray($dbList, $responseProfile);
		$list->loadFlavorParamsIds();
		$response = new BorhanConversionProfileListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;  
	}
}

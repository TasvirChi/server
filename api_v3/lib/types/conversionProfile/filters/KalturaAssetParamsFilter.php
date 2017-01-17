<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanAssetParamsFilter extends BorhanAssetParamsBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new assetParamsFilter();
	}

	protected function doGetListResponse(BorhanFilterPager $pager, array $types = null)
	{
		$flavorParamsFilter = $this->toObject();
		
		$c = new Criteria();
		$flavorParamsFilter->attachToCriteria($c);
		
		$pager->attachToCriteria($c);
		
		if($types)
		{
			$c->add(assetParamsPeer::TYPE, $types, Criteria::IN);
		}
		
		$list = assetParamsPeer::doSelect($c);
		
		$c->setLimit(null);
		$totalCount = assetParamsPeer::doCount($c);

		return array($list, $totalCount);
	}

	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);
		
		$response = new BorhanFlavorParamsListResponse();
		$response->objects = BorhanFlavorParamsArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		return $this->getTypeListResponse($pager, $responseProfile);  
	}
}

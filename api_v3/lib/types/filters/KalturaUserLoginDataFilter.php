<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanUserLoginDataFilter extends BorhanUserLoginDataBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new UserLoginDataFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{	
		$userLoginDataFilter = $this->toObject();
		
		$c = new Criteria();
		$userLoginDataFilter->attachToCriteria($c);
		
		$totalCount = UserLoginDataPeer::doCount($c);
		$pager->attachToCriteria($c);
		$list = UserLoginDataPeer::doSelect($c);
		$newList = BorhanUserLoginDataArray::fromDbArray($list, $responseProfile);
		
		$response = new BorhanUserLoginDataListResponse();
		$response->totalCount = $totalCount;
		$response->objects = $newList;
		return $response;
	}
}

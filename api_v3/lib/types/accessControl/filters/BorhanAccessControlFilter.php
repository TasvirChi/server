<?php
/**
 * @package api
 * @subpackage filters
 * @deprecated use BorhanAccessControlProfileFilter instead
 */
class BorhanAccessControlFilter extends BorhanAccessControlBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new accessControlFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$accessControlFilter = $this->toObject();

		$c = new Criteria();
		$accessControlFilter->attachToCriteria($c);
		
		$totalCount = accessControlPeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = accessControlPeer::doSelect($c);
		
		$list = BorhanAccessControlArray::fromDbArray($dbList, $responseProfile);
		$response = new BorhanAccessControlListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response; 
	}
}

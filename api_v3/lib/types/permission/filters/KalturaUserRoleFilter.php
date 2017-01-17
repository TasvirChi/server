<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanUserRoleFilter extends BorhanUserRoleBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new UserRoleFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$userRoleFilter = $this->toObject();

		$c = new Criteria();
		$userRoleFilter->attachToCriteria($c);
		$count = UserRolePeer::doCount($c);
		
		$pager->attachToCriteria ( $c );
		$list = UserRolePeer::doSelect($c);
		
		$response = new BorhanUserRoleListResponse();
		$response->objects = BorhanUserRoleArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $count;
		
		return $response;
	}
}

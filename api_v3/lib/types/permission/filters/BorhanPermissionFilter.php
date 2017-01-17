<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanPermissionFilter extends BorhanPermissionBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new PermissionFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$permissionFilter = $this->toObject();
		
		$c = new Criteria();
		$permissionFilter->attachToCriteria($c);
		$count = PermissionPeer::doCount($c);
		
		$pager->attachToCriteria ( $c );
		
		$list = PermissionPeer::doSelect($c);
		
		$response = new BorhanPermissionListResponse();
		$response->objects = BorhanPermissionArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $count;
		
		return $response;
	}
}

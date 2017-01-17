<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanPermissionItemFilter extends BorhanPermissionItemBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new PermissionItemFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$permissionItemFilter = $this->toObject();
		
		$c = new Criteria();
		$permissionItemFilter->attachToCriteria($c);
		$count = PermissionItemPeer::doCount($c);
		
		$pager->attachToCriteria ( $c );
		$list = PermissionItemPeer::doSelect($c);
		
		$response = new BorhanPermissionItemListResponse();
		$response->objects = BorhanPermissionItemArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $count;
		
		return $response;
	}
}

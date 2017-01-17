<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanScheduleResourceFilter extends BorhanScheduleResourceBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ScheduleResourceFilter();
	}
	
	protected function getListResponseType()
	{
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$type = $this->getListResponseType();
		
		$c = new Criteria();
		if($type)
		{
			$c->add(ScheduleResourcePeer::TYPE, $type);
		}

		$filter = $this->toObject();
		$filter->attachToCriteria($c);
		$pager->attachToCriteria($c);
			
		$list = ScheduleResourcePeer::doSelect($c);
	
		$resultCount = count($list);
		if ($resultCount && $resultCount < $pager->pageSize)
			$totalCount = ($pager->pageIndex - 1) * $pager->pageSize + $resultCount;
		else
		{
			BorhanFilterPager::detachFromCriteria($c);
			$totalCount = ScheduleResourcePeer::doCount($c);
		}
		
		$response = new BorhanScheduleResourceListResponse();
		$response->objects = BorhanScheduleResourceArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;
	}
}

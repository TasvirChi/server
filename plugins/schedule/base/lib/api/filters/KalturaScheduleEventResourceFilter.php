<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanScheduleEventResourceFilter extends BorhanScheduleEventResourceBaseFilter
{
	/**
	 * Find event-resource objects that associated with the event, if none found, find by its parent event
	 * @var int
	 */
	public $eventIdOrItsParentIdEqual;

	static private $map_between_objects = array
	(
			"eventIdOrItsParentIdEqual" => "_eq_event_id_or_parent",
	);
	
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ScheduleEventResourceFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$c = new Criteria();
		$filter = $this->toObject();
		$filter->attachToCriteria($c);
		$pager->attachToCriteria($c);
			
		$list = ScheduleEventResourcePeer::doSelect($c);
	
		$resultCount = count($list);
		if ($resultCount && $resultCount < $pager->pageSize)
			$totalCount = ($pager->pageIndex - 1) * $pager->pageSize + $resultCount;
		else
		{
			BorhanFilterPager::detachFromCriteria($c);
			$totalCount = ScheduleEventResourcePeer::doCount($c);
		}
		
		$response = new BorhanScheduleEventResourceListResponse();
		$response->objects = BorhanScheduleEventResourceArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;
	}

}

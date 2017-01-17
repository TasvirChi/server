<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanServerNodeFilter extends BorhanServerNodeBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ServerNodeFilter();
	}
	
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, $type = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $type);
		$response = new BorhanServerNodeListResponse();
		$response->objects = BorhanServerNodeArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
	
		return $response;
	}
	
	protected function doGetListResponse(BorhanFilterPager $pager, $type = null)
	{
		$c = new Criteria();
			
		if($type)
			$c->add(ServerNodePeer::TYPE, $type);
			
		$serverNodeFilter = $this->toObject();
		$serverNodeFilter->attachToCriteria($c);
		$pager->attachToCriteria($c);
			
		$list = ServerNodePeer::doSelect($c);
		$totalCount = count($list);
	
		return array($list, $totalCount);
	}

	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		return $this->getTypeListResponse($pager, $responseProfile);
	}
}

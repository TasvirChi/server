<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanEdgeServerNodeFilter extends BorhanEdgeServerNodeBaseFilter
{
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, $type = null)
	{
		if(!$type)
			$type = serverNodeType::EDGE;
	
		return parent::getTypeListResponse($pager, $responseProfile, $type);
	}
}

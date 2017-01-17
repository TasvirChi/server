<?php
/**
 * @package api
 * @subpackage filters
 */
abstract class BorhanRelatedFilter extends BorhanFilter
{
	/**
	 * @param BorhanFilterPager $pager
	 * @param BorhanDetachedResponseProfile $responseProfile
	 * @return BorhanListResponse
	 */
	abstract public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null);
	
	public function validateForResponseProfile()
	{
		
	}
}

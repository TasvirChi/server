<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanFlavorAssetFilter extends BorhanFlavorAssetBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$types = assetPeer::retrieveAllFlavorsTypes();
		return $this->getTypeListResponse($pager, $responseProfile, $types);
	}
}

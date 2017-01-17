<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanLiveAssetFilter extends BorhanLiveAssetBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, assetType::LIVE);
		return $this->getTypeListResponse($pager, $responseProfile, $types);
	}
}

<?php
/**
 * @package plugins.caption
 * @subpackage api.filters
 */
class BorhanCaptionAssetFilter extends BorhanCaptionAssetBaseFilter
{

	static private $map_between_objects = array
	(
		"captionParamsIdEqual" => "_eq_flavor_params_id",
		"captionParamsIdIn" => "_in_flavor_params_id",
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}	

	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);
		
		$response = new BorhanCaptionAssetListResponse();
		$response->objects = BorhanCaptionAssetArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}

	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, CaptionPlugin::getAssetTypeCoreValue(CaptionAssetType::CAPTION));
		return $this->getTypeListResponse($pager, $responseProfile, $types);
	}
}

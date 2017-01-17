<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanThumbAssetFilter extends BorhanThumbAssetBaseFilter
{	
	static private $map_between_objects = array
	(
		"thumbParamsIdEqual" => "_eq_flavor_params_id",
		"thumbParamsIdIn" => "_in_flavor_params_id",
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
		
		$response = new BorhanThumbAssetListResponse();
		$response->objects = BorhanThumbAssetArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}
	
	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, assetType::THUMBNAIL);
		return $this->getTypeListResponse($pager, $responseProfile, $types);
	}
}

<?php
/**
 * @package plugins.document
 * @subpackage api.filters
 */
class BorhanDocumentEntryFilter extends BorhanDocumentEntryBaseFilter
{
	static private $map_between_objects = array
	(
		"assetParamsIdsMatchOr" => "_matchor_flavor_params_ids",
		"assetParamsIdsMatchAnd" => "_matchand_flavor_params_ids",
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseEntryFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager);
		
	    $newList = BorhanDocumentEntryArray::fromDbArray($list, $responseProfile);
		$response = new BorhanBaseEntryListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}

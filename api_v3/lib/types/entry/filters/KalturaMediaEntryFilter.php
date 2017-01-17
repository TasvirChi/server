<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanMediaEntryFilter extends BorhanMediaEntryBaseFilter
{
	static private $map_between_objects = array
	(
		"sourceTypeEqual" => "_eq_source",
		"sourceTypeNotEqual" => "_not_source",
		"sourceTypeIn" => "_in_source",
		"sourceTypeNotIn" => "_notin_source",
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
		
	    $newList = BorhanMediaEntryArray::fromDbArray($list, $responseProfile);
		$response = new BorhanBaseEntryListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}

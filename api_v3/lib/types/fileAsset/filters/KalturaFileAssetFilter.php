<?php
/**
 * @package api
 * @subpackage api.filters
 */
class BorhanFileAssetFilter extends BorhanFileAssetBaseFilter
{
	static private $map_between_objects = array
	(
		"fileAssetObjectTypeEqual" => "_eq_object_type",
	);

	/* (non-PHPdoc)
	 * @see BorhanFileAssetBaseFilter::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new fileAssetFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::toObject()
	 */
	public function toObject ( $object_to_fill = null, $props_to_skip = array() )
	{
		$this->validatePropertyNotNull('fileAssetObjectTypeEqual');
		$this->validatePropertyNotNull(array('objectIdEqual', 'objectIdIn', 'idIn', 'idEqual'));
		return parent::toObject($object_to_fill, $props_to_skip);
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$fileAssetFilter = $this->toObject();

		$c = new Criteria();
		$fileAssetFilter->attachToCriteria($c);
		
		$totalCount = FileAssetPeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = FileAssetPeer::doSelect($c);
		
		$response = new BorhanFileAssetListResponse();
		$response->objects = BorhanFileAssetArray::fromDbArray($dbList, $responseProfile);
		$response->totalCount = $totalCount;
		return $response; 
	}
}

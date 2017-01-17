<?php
/**
 * @package plugins.fileSync
 * @subpackage api.filters
 */
class BorhanFileSyncFilter extends BorhanFileSyncBaseFilter
{
	/**
	 * @var BorhanNullableBoolean
	 */
	public $currentDc;
	
	static private $map_between_objects = array
	(
		"fileObjectTypeEqual" => "_eq_object_type",
		"fileObjectTypeIn" => "_in_object_type",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new FileSyncFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::toObject()
	 */
	public function toObject ( $object_to_fill = null, $props_to_skip = array() )
	{
		if(!$this->isNull('currentDc'))
		{
			if($this->currentDc == BorhanNullableBoolean::TRUE_VALUE)
				$this->dcEqual = kDataCenterMgr::getCurrentDcId();
		}
		
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}

<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.filters
 */
class BorhanDropFolderFilter extends BorhanDropFolderBaseFilter
{
	/**
	 * @var BorhanNullableBoolean
	 */
	public $currentDc;

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DropFolderFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::toObject()
	 */
	public function toObject ( $object_to_fill = null, $props_to_skip = array() )
	{
		if(!$this->isNull('currentDc'))
			$this->dcEqual = kDataCenterMgr::getCurrentDcId();
			
		return parent::toObject($object_to_fill, $props_to_skip);		
	}	
}

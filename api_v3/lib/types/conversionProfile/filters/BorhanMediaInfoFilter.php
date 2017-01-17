<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanMediaInfoFilter extends BorhanMediaInfoBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new MediaInfoFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::toObject()
	 */
	public function toObject ( $object_to_fill = null, $props_to_skip = array() )
	{
		if(!$this->flavorAssetIdEqual)
			throw new BorhanAPIException(BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL, $this->getFormattedPropertyNameWithClassName('flavorAssetIdEqual'));
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}

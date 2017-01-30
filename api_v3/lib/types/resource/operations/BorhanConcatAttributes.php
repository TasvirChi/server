<?php
/**
 * Concat operation attributes
 * 
 * @package api
 * @subpackage objects
 */
class BorhanConcatAttributes extends BorhanOperationAttributes
{
	/**
	 * The resource to be concatenated
	 * @var BorhanDataCenterContentResource
	 */
	public $resource;

	public function toObject ( $object_to_fill = null , $props_to_skip = array() )
	{
		throw new BorhanAPIException(BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED, get_class($this));
		
		if(is_null($object_to_fill))
			$object_to_fill = new kConcatAttributes();
			
		$resource = $this->resource->toObject();
		if($resource instanceof kLocalFileResource)
			$object_to_fill->setFilePath($resource->getLocalFilePath());
			
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}
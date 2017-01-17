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
	}
}
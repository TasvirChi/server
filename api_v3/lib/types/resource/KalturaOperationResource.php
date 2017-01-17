<?php
/**
 * A resource that perform operation (transcoding, clipping, cropping) before the flavor is ready.
 * 
 * @package api
 * @subpackage objects
 */
class BorhanOperationResource extends BorhanContentResource
{
	/**
	 * Only BorhanEntryResource and BorhanAssetResource are supported
	 * @var BorhanContentResource
	 */
	public $resource;
	
	/**
	 * @var BorhanOperationAttributesArray
	 */
	public $operationAttributes;
	
	/**
	 * ID of alternative asset params to be used instead of the system default flavor params 
	 * @var int
	 */
	public $assetParamsId;
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUsage($sourceObject, $propertiesToSkip)
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		parent::validateForUsage($sourceObject, $propertiesToSkip);
		
		$this->validatePropertyNotNull('resource');
		
		if(!($this->resource instanceof BorhanEntryResource) && !($this->resource instanceof BorhanAssetResource))
			throw new BorhanAPIException(BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED, get_class($this->resource));
	}
	
	/* (non-PHPdoc)
	 * @see BorhanResource::validateEntry()
	 */
	public function validateEntry(entry $dbEntry)
	{
		parent::validateEntry($dbEntry);
		
		$this->resource->validateEntry($dbEntry);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanResource::entryHandled()
	 */
	public function entryHandled(entry $dbEntry)
	{
		parent::entryHandled($dbEntry);
		$this->resource->entryHandled($dbEntry);
	}
	
	private static $map_between_objects = array('assetParamsId');
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		$this->validateForUsage($object_to_fill, $props_to_skip);
		
		if(is_null($this->operationAttributes) || !count($this->operationAttributes))
			return $this->resource->toObject();
		
		if(!$object_to_fill)
			$object_to_fill = new kOperationResource();
		
		$operationAttributes = array();
		foreach($this->operationAttributes as $operationAttributesObject)
			$operationAttributes[] = $operationAttributesObject->toObject();
		
		$object_to_fill->setOperationAttributes($operationAttributes);
		$object_to_fill->setResource($this->resource->toObject());
		
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}
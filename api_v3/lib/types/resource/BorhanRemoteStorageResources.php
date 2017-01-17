<?php
/**
 * Used to ingest media that is available on remote server and accessible using the supplied URL, the media file won't be downloaded but a file sync object of URL type will point to the media URL.
 * 
 * @package api
 * @subpackage objects
 */
class BorhanRemoteStorageResources extends BorhanContentResource
{
	/**
	 * Array of remote stoage resources 
	 * @var BorhanRemoteStorageResourceArray
	 */
	public $resources;

	private static $map_between_objects = array
	(
		'resources',
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUsage($sourceObject, $propertiesToSkip)
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		parent::validateForUsage($sourceObject, $propertiesToSkip);
		
		$this->validatePropertyNotNull('resources');
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		if(!$object_to_fill)
			$object_to_fill = new kRemoteStorageResources();
		
		$resources = array();
		if($this->resources)
		{
			foreach($this->resources as $resource)
			{
				/* @var $resource BorhanRemoteStorageResource */
				$resources[] = $resource->toObject();
			}
		}
		$object_to_fill->setResources($resources);
		
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}
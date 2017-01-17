<?php
/**
 * Advanced configuration for entry replacement process
 * @package api
 * @subpackage objects
 */
class BorhanEntryReplacementOptions extends BorhanObject
{
	/**
	 * If true manually created thumbnails will not be deleted on entry replacement
	 * @var int
	 */
	public $keepManualThumbnails;

	/**
	 * Array of plugin replacement options
	 * @var BorhanPluginReplacementOptionsArray
	 */
	public $pluginOptionItems;

	private static $mapBetweenObjects = array
	(
		'keepManualThumbnails',
		'pluginOptionItems',
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		if(!$object_to_fill)
			$object_to_fill = new kEntryReplacementOptions();
		
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}

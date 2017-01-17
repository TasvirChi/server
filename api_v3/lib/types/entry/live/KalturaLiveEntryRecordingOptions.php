<?php
/**
 * A representation of a live stream recording entry configuration
 * 
 * @package api
 * @subpackage objects
 */
class BorhanLiveEntryRecordingOptions extends BorhanObject
{
	
	/**
	 * @var BorhanNullableBoolean
	 */
	public $shouldCopyEntitlement;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $shouldCopyScheduling;
	
	/**
	 * @var BorhanNullableBoolean
	 */
	public $shouldCopyThumbnail;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $shouldMakeHidden;

	private static $mapBetweenObjects = array
	(
		"shouldCopyEntitlement",
		"shouldCopyScheduling",
		"shouldCopyThumbnail",
		"shouldMakeHidden",
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
	public function toObject($dbObject = null, $propsToSkip = array())
	{
		if (!$dbObject)
		{
			$dbObject = new kLiveEntryRecordingOptions();
		}
		
		return parent::toObject($dbObject, $propsToSkip);
	}
}
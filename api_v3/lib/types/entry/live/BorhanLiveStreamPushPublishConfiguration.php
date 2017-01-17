<?php
/**
 * Basic push-publish configuration for Borhan live stream entry
 * @package api
 * @subpackage objects
 *
 */
class BorhanLiveStreamPushPublishConfiguration extends BorhanObject
{
	/**
	 * @var string
	 */
	public $publishUrl;
	
	/**
	 * @var string
	 */
	public $backupPublishUrl;
	
	/**
	 * @var string
	 */
	public $port;
	
	private static $mapBetweenObjects = array
	(
		"publishUrl", "backupPublishUrl" , "port",
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
			$dbObject = new kLiveStreamPushPublishConfiguration();
		}
		
		return parent::toObject($dbObject, $propsToSkip);
	}
	
	public static function getInstance ($className)
	{
		switch ($className)
		{
			case 'kLiveStreamPushPublishRTMPConfiguration':
				return new BorhanLiveStreamPushPublishRTMPConfiguration();
			default:
				return new BorhanLiveStreamPushPublishConfiguration();
		}
	}
}
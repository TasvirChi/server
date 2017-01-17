<?php
/**
 * A representation of an RTMP live stream configuration
 * 
 * @package api
 * @subpackage objects
 */
class BorhanLiveStreamPushPublishRTMPConfiguration extends BorhanLiveStreamPushPublishConfiguration
{
	/**
	 * @var string
	 */
	public $userId;
	
	/**
	 * @var string
	 */
	public $password;
	
	/**
	 * @var string
	 */
	public $streamName;
	
	/**
	 * @var string
	 */
	public $applicationName;
	
	private static $mapBetweenObjects = array
	(
		"userId", "password", "streamName", "applicationName"
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
			$dbObject = new kLiveStreamPushPublishRTMPConfiguration();
		}
		
		return parent::toObject($dbObject, $propsToSkip);
	}
}
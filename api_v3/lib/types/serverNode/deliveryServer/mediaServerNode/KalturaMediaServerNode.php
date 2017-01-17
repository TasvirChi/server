<?php
/**
 * @package api
 * @subpackage objects
 */
abstract class BorhanMediaServerNode extends BorhanDeliveryServerNode
{
	/**
	 * Media server application name
	 *
	 * @var string
	 */
	public $applicationName;
			
	/**
	 * Media server playback port configuration by protocol and format
	 *
	 * @var BorhanKeyValueArray
	 */
	public $mediaServerPortConfig;
	
	/**
	 * Media server playback Domain configuration by protocol and format
	 *
	 * @var BorhanKeyValueArray
	 * @deprecated Use Delivery Profile Ids instead
	 * 
	 */
	public $mediaServerPlaybackDomainConfig;
	
	private static $mapBetweenObjects = array
	(
		'applicationName',
		'mediaServerPortConfig',
		'mediaServerPlaybackDomainConfig',
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}
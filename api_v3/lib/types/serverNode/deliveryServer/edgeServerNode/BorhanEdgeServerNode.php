<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanEdgeServerNode extends BorhanDeliveryServerNode
{
	/**
	 * Delivery server playback Domain
	 *
	 * @var string
	 * @filter like,mlikeor,mlikeand
	 */
	public $playbackDomain;
	
	/**
	 * Overdie edge server default configuration - json format
	 * @var string
	 */
	public $config;
	
	private static $map_between_objects = array
	(
		"playbackDomain" => "playbackHostName",
		"config",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		return parent::validateForInsertByType($propertiesToSkip, serverNodeType::EDGE);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		return parent::validateForUpdateByType($sourceObject, $propertiesToSkip, serverNodeType::EDGE);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toInsertableObject()
	 */
	public function toInsertableObject($object_to_fill = null, $props_to_skip = array())
	{
		if(is_null($object_to_fill))
			$object_to_fill = new EdgeServerNode();
			
		return parent::toInsertableObject($object_to_fill, $props_to_skip);
	}
}
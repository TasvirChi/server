<?php
/**
 * @package plugins.eventCuePoint
 * @subpackage api.objects
 * @requiresPermission insert,update
 */
class BorhanEventCuePoint extends BorhanCuePoint
{
	/**
	 * @var BorhanEventType 
	 * @filter eq,in
	 * @requiresPermission insert,update
	 */
	public $eventType;
	
	public function __construct()
	{
		$this->cuePointType = EventCuePointPlugin::getApiValue(EventCuePointType::EVENT);
	}
	
	private static $map_between_objects = array
	(
		"eventType" => "subType",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanCuePoint::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toInsertableObject()
	 */
	public function toInsertableObject($object_to_fill = null, $props_to_skip = array())
	{
		if(is_null($object_to_fill))
			$object_to_fill = new EventCuePoint();
			
		return parent::toInsertableObject($object_to_fill, $props_to_skip);
	}
	
}

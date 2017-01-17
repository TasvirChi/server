<?php
/**
 * @package plugins.playReady
 * @subpackage api.objects
 */
class BorhanPlayReadyCopyEnablerHolder extends BorhanObject
{
	/**
	 * The type of the copy enabler
	 * 
	 * @var BorhanPlayReadyCopyEnablerType
	 */
	public $type;
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		return $this->type;
	}
	
	private static $mapBetweenObjects = array
	(
		'type',
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}
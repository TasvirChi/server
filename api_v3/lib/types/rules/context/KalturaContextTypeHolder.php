<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanContextTypeHolder extends BorhanObject
{
	/**
	 * The type of the condition context
	 * 
	 * @var BorhanContextType
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
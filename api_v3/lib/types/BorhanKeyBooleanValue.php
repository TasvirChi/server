<?php
/**
 * A key (boolean) value pair representation to return an array of key-(boolean)value pairs (associative array)
 * 
 * @see BorhanKeyBooleanValueArray
 * @package api
 * @subpackage objects
 */
class BorhanKeyBooleanValue extends BorhanObject
{
	/**
	 * @var string
	 */
	public $key;
    
	/**
	 * @var bool
	 */
	public $value;
    
	private static $mapBetweenObjects = array
	(
		"key", "value",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}
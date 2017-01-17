<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCountryCondition extends BorhanMatchCondition
{
	/**
	 * The ip geo coder engine to be used
	 * 
	 * @var BorhanGeoCoderType
	 */
	public $geoCoderType;

	private static $mapBetweenObjects = array
	(
		'geoCoderType',
	);

	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = ConditionType::COUNTRY;
	}
		
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kCountryCondition();
			
		return parent::toObject($dbObject, $skip);
	}
}

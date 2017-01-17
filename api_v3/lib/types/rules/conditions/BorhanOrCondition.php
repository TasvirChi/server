<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanOrCondition extends BorhanCondition
{
	/**
	 * @var BorhanConditionArray
	 */
	public $conditions;
	
	private static $mapBetweenObjects = array
	(
		'conditions',
	);
	
	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = ConditionType::OR_OPERATOR;
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
			$dbObject = new kOrCondition();
			
		return parent::toObject($dbObject, $skip);
	}
}

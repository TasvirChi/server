<?php
/**
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanCompareCondition extends BorhanCondition
{
	/**
	 * Value to evaluate against the field and operator
	 * @var BorhanIntegerValue
	 */
	public $value;
	
	/**
	 * Comparing operator
	 * @var BorhanSearchConditionComparison
	 */
	public $comparison;
	
	private static $mapBetweenObjects = array
	(
		'comparison',
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		/* @var $dbObject kCompareCondition */
		$dbObject->setValue($this->value->toObject());
			
		return parent::toObject($dbObject, $skip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kFieldMatchCondition */
		parent::doFromObject($dbObject, $responseProfile);
		
		$valueType = get_class($dbObject->getValue());
		BorhanLog::debug("Loading BorhanIntegerValue from type [$valueType]");
		switch ($valueType)
		{
			case 'kIntegerValue':
				$this->value = new BorhanIntegerValue();
				break;
				
			case 'kTimeContextField':
				$this->value = new BorhanTimeContextField();
				break;
				
			default:
				$this->value = BorhanPluginManager::loadObject('BorhanIntegerValue', $valueType);
				break;
		}
		
		if($this->value)
			$this->value->fromObject($dbObject->getValue());
	}
}

<?php
/**
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanMatchCondition extends BorhanCondition
{
	/**
	 * @var BorhanStringValueArray
	 */
	public $values;
	
	private static $mapBetweenObjects = array
	(
		'values',
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}

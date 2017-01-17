<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanDeliveryProfileCondition extends BorhanCondition
{
	/**
	 * The delivery ids that are accepted by this condition
	 * 
	 * @var BorhanIntegerValueArray
	 */
	public $deliveryProfileIds;
	
	private static $mapBetweenObjects = array
	(
		'deliveryProfileIds',
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
		if(!$dbObject)
			$dbObject = new kDeliveryProfileCondition();
		return parent::toObject($dbObject, $skip);
	}
}

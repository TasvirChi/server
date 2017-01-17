<?php
/**
 * @package api
 * @subpackage objects
 */
abstract class BorhanDeliveryServerNode extends BorhanServerNode
{
	/**
	 * Delivery profile ids
	 * @var BorhanKeyValueArray
	 */
	public $deliveryProfileIds;

	private static $map_between_objects = array 
	(
		"deliveryProfileIds",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
}
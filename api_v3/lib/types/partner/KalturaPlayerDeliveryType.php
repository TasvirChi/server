<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPlayerDeliveryType extends BorhanObject
{
	/**
	 * @var string
	 */
	public $id;
	
	/**
	 * @var string
	 */
	public $label;
	
	/**
	 * @var BorhanKeyValueArray
	 */
	public $flashvars;
	
	/**
	 * @var string
	 */
	public $minVersion;

	/**
	 * @var bool
	 */
	public $enabledByDefault = false;
	
	private static $map_between_objects = array(
		'label', 
		'flashvars',
		'minVersion',
		'enabledByDefault'
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
}
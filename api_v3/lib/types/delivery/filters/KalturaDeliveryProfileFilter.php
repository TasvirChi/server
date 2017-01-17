<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanDeliveryProfileFilter extends BorhanDeliveryProfileBaseFilter
{
	/**
	 * @var BorhanNullableBoolean
	 */
	public $isLive;
	
	static private $map_between_objects = array
	(
		"isLive" => "_is_live",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DeliveryProfileFilter();
	}
}

<?php
/**
 * @package plugins.exampleDistribution
 * @subpackage api.filters.base
 * @abstract
 */
abstract class BorhanExampleDistributionProfileBaseFilter extends BorhanDistributionProfileFilter
{
	static private $map_between_objects = array
	(
	);

	static private $order_by_map = array
	(
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), BorhanExampleDistributionProfileBaseFilter::$map_between_objects);
	}

	public function getOrderByMap()
	{
		return array_merge(parent::getOrderByMap(), BorhanExampleDistributionProfileBaseFilter::$order_by_map);
	}
}

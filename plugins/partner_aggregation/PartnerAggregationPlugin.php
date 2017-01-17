<?php
/**
 * @package plugins.partnerAggregation
 */
class PartnerAggregationPlugin extends BorhanPlugin implements IBorhanServices
{
	const PLUGIN_NAME = 'partnerAggregation';

	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/**
	 * @return array<string,string> in the form array[serviceName] = serviceClass
	 */
	public static function getServicesMap()
	{
		$map = array(
			'partnerAggregation' => 'PartnerAggregationService',
		);
		return $map;
	}
}

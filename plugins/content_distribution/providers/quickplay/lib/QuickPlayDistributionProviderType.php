<?php
/**
 * @package plugins.quickPlayDistribution
 * @subpackage lib
 */
class QuickPlayDistributionProviderType implements IBorhanPluginEnum, DistributionProviderType
{
	const QUICKPLAY = 'QUICKPLAY';
	
	public static function getAdditionalValues()
	{
		return array(
			'QUICKPLAY' => self::QUICKPLAY,
		);
	}
	
	/**
	 * @return array
	 */
	public static function getAdditionalDescriptions()
	{
		return array();
	}
}

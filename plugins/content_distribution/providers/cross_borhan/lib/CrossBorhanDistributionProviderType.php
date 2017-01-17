<?php
/**
 * @package plugins.crossBorhanDistribution
 * @subpackage lib
 */
class CrossBorhanDistributionProviderType implements IBorhanPluginEnum, DistributionProviderType
{
	const CROSS_BORHAN = 'CROSS_BORHAN';
	
	public static function getAdditionalValues()
	{
		return array(
			'CROSS_BORHAN' => self::CROSS_BORHAN,
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

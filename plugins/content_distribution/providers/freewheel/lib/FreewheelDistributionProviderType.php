<?php
/**
 * @package plugins.freewheelDistribution
 * @subpackage lib
 */
class FreewheelDistributionProviderType implements IBorhanPluginEnum, DistributionProviderType
{
	const FREEWHEEL = 'FREEWHEEL';
	
	public static function getAdditionalValues()
	{
		return array(
			'FREEWHEEL' => self::FREEWHEEL,
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

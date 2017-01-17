<?php
/**
 * @package plugins.freewheelGenericDistribution
 * @subpackage lib
 */
class FreewheelGenericDistributionProviderType implements IBorhanPluginEnum, DistributionProviderType
{
	const FREEWHEEL_GENERIC = 'FREEWHEEL_GENERIC';
	
	public static function getAdditionalValues()
	{
		return array(
			'FREEWHEEL_GENERIC' => self::FREEWHEEL_GENERIC,
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

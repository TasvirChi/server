<?php
/**
 * @package plugins.facebookDistribution
 * @subpackage lib
 */
class FacebookDistributionProviderType implements IBorhanPluginEnum, DistributionProviderType
{
	const FACEBOOK = 'FACEBOOK';
	
	public static function getAdditionalValues()
	{
		return array(
			'FACEBOOK' => self::FACEBOOK,
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

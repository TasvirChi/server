<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.enum
 */
class BorhanDistributionProviderType extends BorhanDynamicEnum implements DistributionProviderType
{
	public static function getEnumClass()
	{
		return 'DistributionProviderType';
	}
}
<?php
/**
 * @package plugins.virusScan
 * @subpackage api.objects
 */
class BorhanVirusScanEngineType extends BorhanDynamicEnum implements VirusScanEngineType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'VirusScanEngineType';
	}
}
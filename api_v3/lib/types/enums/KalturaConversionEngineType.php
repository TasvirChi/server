<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanConversionEngineType extends BorhanDynamicEnum implements conversionEngineType
{
	public static function getEnumClass()
	{
		return 'conversionEngineType';
	}
}

<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanConversionProfileType extends BorhanDynamicEnum implements ConversionProfileType
{
	public static function getEnumClass()
	{
		return 'ConversionProfileType';
	}
}

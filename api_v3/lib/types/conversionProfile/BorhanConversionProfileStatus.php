<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanConversionProfileStatus extends BorhanDynamicEnum implements ConversionProfileStatus
{
	public static function getEnumClass()
	{
		return 'ConversionProfileStatus';
	}
}

<?php
/**
 * @package plugins.adCuePoint
 * @subpackage api.enum
 */
class BorhanAdType extends BorhanDynamicEnum implements AdType
{
	public static function getEnumClass()
	{
		return 'AdType';
	}
}
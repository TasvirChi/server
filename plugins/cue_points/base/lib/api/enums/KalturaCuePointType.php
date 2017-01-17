<?php
/**
 * @package plugins.cuePoint
 * @subpackage api.enum
 */
class BorhanCuePointType extends BorhanDynamicEnum implements CuePointType
{
	public static function getEnumClass()
	{
		return 'CuePointType';
	}
}
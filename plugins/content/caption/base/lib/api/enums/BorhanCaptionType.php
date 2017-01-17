<?php
/**
 * @package plugins.caption
 * @subpackage api.enum
 */
class BorhanCaptionType extends BorhanDynamicEnum implements CaptionType
{
	public static function getEnumClass()
	{
		return 'CaptionType';
	}
}
<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanUserEntryType extends BorhanDynamicEnum implements UserEntryType
{
	public static function getEnumClass()
	{
		return 'UserEntryType';
	}
}
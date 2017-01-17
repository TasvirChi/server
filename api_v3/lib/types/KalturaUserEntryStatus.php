<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanUserEntryStatus extends BorhanDynamicEnum implements UserEntryStatus
{
	public static function getEnumClass()
	{
		return 'UserEntryStatus';
	}
}


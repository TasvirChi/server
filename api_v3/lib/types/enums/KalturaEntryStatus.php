<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanEntryStatus extends BorhanDynamicEnum implements entryStatus
{
	public static function getEnumClass()
	{
		return 'entryStatus';
	}
}
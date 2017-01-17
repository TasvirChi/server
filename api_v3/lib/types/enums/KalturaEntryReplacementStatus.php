<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanEntryReplacementStatus extends BorhanDynamicEnum implements entryReplacementStatus
{
	public static function getEnumClass()
	{
		return 'entryReplacementStatus';
	}
}
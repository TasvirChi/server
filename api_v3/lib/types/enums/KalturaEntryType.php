<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanEntryType extends BorhanDynamicEnum implements entryType
{
	public static function getEnumClass()
	{
		return 'entryType';
	}
}

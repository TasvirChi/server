<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanEntryServerNodeType extends BorhanDynamicEnum implements EntryServerNodeType
{
	public static function getEnumClass()
	{
		return 'EntryServerNodeType';
	}
}
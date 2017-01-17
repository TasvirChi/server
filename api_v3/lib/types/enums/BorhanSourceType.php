<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanSourceType extends BorhanDynamicEnum implements EntrySourceType
{
	public static function getEnumClass()
	{
		return 'EntrySourceType';
	}
}

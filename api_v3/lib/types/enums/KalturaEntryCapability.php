<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanEntryCapability extends BorhanDynamicEnum implements EntryCapability
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'EntryCapability';
	}
}

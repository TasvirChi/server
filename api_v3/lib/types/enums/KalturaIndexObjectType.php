<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanIndexObjectType extends BorhanDynamicEnum implements IndexObjectType
{
	public static function getEnumClass()
	{
		return 'IndexObjectType';
	}
}
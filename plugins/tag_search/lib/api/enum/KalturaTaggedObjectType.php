<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanTaggedObjectType extends BorhanDynamicEnum implements taggedObjectType
{
	public static function getEnumClass()
	{
		return 'taggedObjectType';
	}
}
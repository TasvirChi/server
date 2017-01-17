<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanModerationObjectType extends BorhanDynamicEnum implements moderationObjectType
{
	public static function getEnumClass()
	{
		return 'moderationObjectType';
	}
}
<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.enum
 * @see ObjectFilterEngineType
 */
class BorhanObjectFilterEngineType extends BorhanDynamicEnum implements ObjectFilterEngineType
{
	public static function getEnumClass()
	{
		return 'ObjectFilterEngineType';
	}
}
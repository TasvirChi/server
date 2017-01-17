<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanContextType extends BorhanDynamicEnum implements ContextType
{
	public static function getEnumClass()
	{
		return 'ContextType';
	}
}
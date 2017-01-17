<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanServerNodeType extends BorhanDynamicEnum implements serverNodeType
{
	public static function getEnumClass()
	{
		return 'serverNodeType';
	}
}
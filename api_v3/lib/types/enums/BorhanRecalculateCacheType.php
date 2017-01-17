<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanRecalculateCacheType extends BorhanDynamicEnum implements RecalculateCacheType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'RecalculateCacheType';
	}
}

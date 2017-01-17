<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanGeoCoderType extends BorhanDynamicEnum implements geoCoderType
{
	public static function getEnumClass()
	{
		return 'geoCoderType';
	}
}
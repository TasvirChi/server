<?php
/**
 * @package api
 * @subpackage filters.enum
 */
class BorhanFileAssetObjectType extends BorhanDynamicEnum implements FileAssetObjectType
{
	public static function getEnumClass()
	{
		return 'FileAssetObjectType';
	}
}
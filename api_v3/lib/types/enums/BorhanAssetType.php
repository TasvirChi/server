<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanAssetType extends BorhanDynamicEnum implements assetType
{
	public static function getEnumClass()
	{
		return 'assetType';
	}
}

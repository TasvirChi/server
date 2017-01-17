<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanFileAssetStatus extends BorhanDynamicEnum implements FileAssetStatus
{
	public static function getEnumClass()
	{
		return 'FileAssetStatus';
	}
}
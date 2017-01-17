<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanStorageProfileProtocol extends BorhanDynamicEnum implements StorageProfileProtocol
{
	public static function getEnumClass()
	{
		return 'StorageProfileProtocol';
	}
}

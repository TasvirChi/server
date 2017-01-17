<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.enum
 */
class BorhanDropFolderErrorCode extends BorhanDynamicEnum implements DropFolderErrorCode
{
	// see DropFolderErrorCode interface
	
	public static function getEnumClass()
	{
		return 'DropFolderErrorCode';
	}
}

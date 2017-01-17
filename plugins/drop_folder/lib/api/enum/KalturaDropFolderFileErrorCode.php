<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.enum
 */
class BorhanDropFolderFileErrorCode extends BorhanDynamicEnum implements DropFolderFileErrorCode
{
	// see DropFolderFileErrorCode interface
	
	public static function getEnumClass()
	{
		return 'DropFolderFileErrorCode';
	}
}

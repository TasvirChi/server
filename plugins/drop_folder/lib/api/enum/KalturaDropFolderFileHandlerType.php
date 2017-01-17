<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.enum
 */
class BorhanDropFolderFileHandlerType extends BorhanDynamicEnum implements DropFolderFileHandlerType
{
	public static function getEnumClass()
	{
		return 'DropFolderFileHandlerType';
	}
}
<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.enum
 */
class BorhanDropFolderType extends BorhanDynamicEnum implements DropFolderType
{
	public static function getEnumClass()
	{
		return 'DropFolderType';
	}
}
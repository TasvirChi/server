<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanPermissionName extends BorhanDynamicEnum implements PermissionName
{
	// see permissionName interface
	
	public static function getEnumClass()
	{
		return 'PermissionName';
	}
}
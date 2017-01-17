<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanFileSyncObjectType extends BorhanDynamicEnum implements FileSyncObjectType 
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'FileSyncObjectType';
	}
}
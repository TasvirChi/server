<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanBulkUploadObjectType extends BorhanDynamicEnum implements BulkUploadObjectType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'BulkUploadObjectType';
	}
}
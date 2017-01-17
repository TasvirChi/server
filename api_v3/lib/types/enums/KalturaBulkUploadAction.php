<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanBulkUploadAction extends BorhanDynamicEnum implements BulkUploadAction
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'BulkUploadAction';
	}
}

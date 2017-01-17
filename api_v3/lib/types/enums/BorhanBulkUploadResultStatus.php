<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanBulkUploadResultStatus extends BorhanDynamicEnum implements BulkUploadResultStatus
{
	public static function getEnumClass()
	{
		return 'BulkUploadResultStatus';
	}
}
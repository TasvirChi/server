<?php

/**
 * 
 * Represents the bulk upload type
 * @author Roni
 * @package api
 * @subpackage enum
 *
 */
class BorhanBulkUploadType extends BorhanDynamicEnum implements BulkUploadType
{
	public static function getEnumClass()
	{
		return 'BulkUploadType';
	}
}
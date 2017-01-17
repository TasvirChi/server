<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanBatchJobObjectType extends BorhanDynamicEnum implements BatchJobObjectType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'BatchJobObjectType';
	}
}

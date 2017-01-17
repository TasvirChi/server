<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanBatchJobType extends BorhanDynamicEnum implements BatchJobType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'BatchJobType';
	}
}

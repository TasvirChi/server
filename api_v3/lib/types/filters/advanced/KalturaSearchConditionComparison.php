<?php
/**
 * @package api
 * @subpackage filters.enum
 */
class BorhanSearchConditionComparison extends BorhanDynamicEnum implements searchConditionComparison
{
	public static function getEnumClass()
	{
		return 'searchConditionComparison';
	}
}
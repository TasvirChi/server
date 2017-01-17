<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanReportType extends BorhanDynamicEnum implements ReportType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'ReportType';
	}

}

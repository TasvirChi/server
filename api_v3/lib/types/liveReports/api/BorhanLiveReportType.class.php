<?php

/**
 * @package api
 * @subpackage model.enum
 */
class BorhanLiveReportType extends BorhanStringEnum implements BaseEnum
{				
	const PARTNER_TOTAL = 'PARTNER_TOTAL';
	const ENTRY_TOTAL = 'ENTRY_TOTAL';
	const ENTRY_TIME_LINE = 'ENTRY_TIME_LINE';
	const ENTRY_GEO_TIME_LINE = 'ENTRY_GEO_TIME_LINE';
	const ENTRY_SYNDICATION_TOTAL = 'ENTRY_SYNDICATION_TOTAL';
	
	/* (non-PHPdoc)
	 * @see IBorhanDynamicEnum::getEnumClass()
	 */
	public static function getEnumClass() {
		return 'LiveReportType';
	}
}









































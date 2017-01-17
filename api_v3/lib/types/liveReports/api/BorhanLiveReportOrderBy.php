<?php

/**
 * @package api
 * @subpackage model.enum
 */
class BorhanLiveReportOrderBy extends BorhanStringEnum
{
	const EVENT_TIME_DESC = "-eventTime";
	const PLAYS_DESC = "-plays";
	const AUDIENCE_DESC = "-audience";
	const NAME_ASC = "+name";
}
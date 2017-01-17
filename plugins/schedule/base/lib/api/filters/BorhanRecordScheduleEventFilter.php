<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanRecordScheduleEventFilter extends BorhanRecordScheduleEventBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanScheduleEventFilter::getListResponseType()
	 */
	protected function getListResponseType()
	{
		return ScheduleEventType::RECORD;
	}
}

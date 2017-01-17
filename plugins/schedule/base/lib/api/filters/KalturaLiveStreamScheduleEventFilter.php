<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanLiveStreamScheduleEventFilter extends BorhanLiveStreamScheduleEventBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanScheduleEventFilter::getListResponseType()
	 */
	protected function getListResponseType()
	{
		return ScheduleEventType::LIVE_STREAM;
	}
}

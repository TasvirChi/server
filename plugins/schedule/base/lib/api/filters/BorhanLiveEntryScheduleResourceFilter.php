<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanLiveEntryScheduleResourceFilter extends BorhanLiveEntryScheduleResourceBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanScheduleResourceFilter::getListResponseType()
	 */
	protected function getListResponseType()
	{
		return ScheduleResourceType::LIVE_ENTRY;
	}
}

<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanLocationScheduleResourceFilter extends BorhanLocationScheduleResourceBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanScheduleResourceFilter::getListResponseType()
	 */
	protected function getListResponseType()
	{
		return ScheduleResourceType::LOCATION;
	}
}

<?php
/**
 * @package plugins.scheduledTask
 * @subpackage api.filters
 */
class BorhanScheduledTaskProfileFilter extends BorhanScheduledTaskProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ScheduledTaskProfileFilter();
	}
}

<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.filters
 */
class BorhanEventNotificationTemplateFilter extends BorhanEventNotificationTemplateBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new EventNotificationTemplateFilter();
	}
}

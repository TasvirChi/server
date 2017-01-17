<?php
/**
 * @package plugins.eventNotification
 * @subpackage Scheduler
 */
abstract class KDispatchEventNotificationEngine
{	
	
	/**
	 * @param BorhanEventNotificationTemplate $eventNotificationTemplate
	 * @param BorhanEventNotificationDispatchJobData $data
	 */
	abstract public function dispatch(BorhanEventNotificationTemplate $eventNotificationTemplate, BorhanEventNotificationDispatchJobData &$data);
}

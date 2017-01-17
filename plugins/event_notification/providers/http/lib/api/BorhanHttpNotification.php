<?php
/**
 * Wrapper for sent notifications 
 * 
 * @package plugins.httpNotification
 * @subpackage api.objects
 */
class BorhanHttpNotification extends BorhanObject
{
	/**
	 * Object that triggered the notification
	 * @var BorhanObject
	 */
	public $object;
	
	/**
	 * Object type that triggered the notification
	 * @var BorhanEventNotificationEventObjectType
	 */
	public $eventObjectType;
	
	/**
	 * ID of the batch job that execute the notification
	 * @var bigint
	 */
	public $eventNotificationJobId;
	
	/**
	 * ID of the template that triggered the notification
	 * @var int
	 */
	public $templateId;
	
	/**
	 * Name of the template that triggered the notification
	 * @var string
	 */
	public $templateName;
	
	/**
	 * System name of the template that triggered the notification
	 * @var string
	 */
	public $templateSystemName;
	
	/**
	 * Ecent type that triggered the notification
	 * @var BorhanEventNotificationEventType
	 */
	public $eventType;
}

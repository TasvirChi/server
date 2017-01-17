<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.enum
 * @see EventNotificationEventObjectType
 */
class BorhanEventNotificationEventObjectType extends BorhanDynamicEnum implements EventNotificationEventObjectType
{
	public static function getEnumClass()
	{
		return 'EventNotificationEventObjectType';
	}
}
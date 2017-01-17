<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.enum
 * @see EventNotificationTemplateType
 */
class BorhanEventNotificationTemplateType extends BorhanDynamicEnum implements EventNotificationTemplateType
{
	public static function getEnumClass()
	{
		return 'EventNotificationTemplateType';
	}
}
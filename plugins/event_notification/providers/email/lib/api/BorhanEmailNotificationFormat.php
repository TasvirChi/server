<?php
/**
 * @package plugins.emailNotification
 * @subpackage api.enum
 * @see EmailNotificationFormat
 */
class BorhanEmailNotificationFormat extends BorhanDynamicEnum implements EmailNotificationFormat
{
	public static function getEnumClass()
	{
		return 'EmailNotificationFormat';
	}
}
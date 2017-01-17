<?php
/**
 * Enum class for recipient provider types
 * 
 * @package plugins.emailNotification
 * @subpackage api.enums
 */
class BorhanEmailNotificationRecipientProviderType extends BorhanDynamicEnum implements EmailNotificationRecipientProviderType 
{
	public static function getEnumClass()
	{
		return 'EmailNotificationRecipientProviderType';
	}
}
<?php
/**
 * Abstract core class  which provides the recipients (to, CC, BCC) for an email notification
 * @package plugins.emailNotification
 * @subpackage model.data
 */
abstract class BorhanEmailNotificationRecipientProvider extends BorhanObject
{
	public static function getProviderInstance ($dbObject)
	{
		switch (get_class($dbObject))
		{
			case 'kEmailNotificationStaticRecipientProvider':
				$instance = new BorhanEmailNotificationStaticRecipientProvider();
				break;
			case 'kEmailNotificationCategoryRecipientProvider':
				$instance = new BorhanEmailNotificationCategoryRecipientProvider();
				break;
			case 'kEmailNotificationUserRecipientProvider':
				$instance = new BorhanEmailNotificationUserRecipientProvider();
				break;
			default:
				$instance = BorhanPluginManager::loadObject('kEmailNotificationRecipientProvider', get_class($dbObject));
				break;
		}
		
		if ($instance)
			$instance->fromObject($dbObject);
		
		return $instance;
	}
}
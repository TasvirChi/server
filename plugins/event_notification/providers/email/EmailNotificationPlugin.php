<?php
/**
 * @package plugins.emailNotification
 * 
 * 
 * TODO
 * Add event consumer to consume new email jobs and dispath event notification instead
 * Untill all mails are sent throgh events
 */
class EmailNotificationPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanPending, IBorhanObjectLoader, IBorhanEnumerator
{
	const PLUGIN_NAME = 'emailNotification';
	
	const EVENT_NOTIFICATION_PLUGIN_NAME = 'eventNotification';
	const EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR = 1;
	const EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR = 0;
	const EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD = 0;
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId)
	{
		$partner = PartnerPeer::retrieveByPK($partnerId);
		if ($partner)
			return $partner->getPluginEnabled(self::PLUGIN_NAME);

		return false;
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('EmailNotificationTemplateType', 'EmailNotificationFileSyncObjectType');
	
		if($baseEnumName == 'EventNotificationTemplateType')
			return array('EmailNotificationTemplateType');
			
		if($baseEnumName == 'FileSyncObjectType')
			return array('EmailNotificationFileSyncObjectType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'ISyncableFile' && $enumValue == self::getEmailNotificationFileSyncObjectTypeCoreValue(EmailNotificationFileSyncObjectType::EMAIL_NOTIFICATION_TEMPLATE) && isset($constructorArgs['objectId']))
			return EventNotificationTemplatePeer::retrieveTypeByPK(self::getEmailNotificationTemplateTypeCoreValue(EmailNotificationTemplateType::EMAIL), $constructorArgs['objectId']);
		
			
		if ($baseClass == 'KEmailNotificationRecipientEngine')
		{
			list($recipientJobData) = $constructorArgs;
			switch ($enumValue)	
			{
				case BorhanEmailNotificationRecipientProviderType::CATEGORY:
					return new KEmailNotificationCategoryRecipientEngine($recipientJobData);
					break;
				case BorhanEmailNotificationRecipientProviderType::STATIC_LIST:
					return new KEmailNotificationStaticRecipientEngine($recipientJobData);
					break;
				case BorhanEmailNotificationRecipientProviderType::USER:
					return new KEmailNotificationUserRecipientEngine($recipientJobData);
					break;
			}
		}
		
		$class = self::getObjectClass($baseClass, $enumValue);
		if($class)
		{
			if(is_array($constructorArgs))
			{
				$reflect = new ReflectionClass($class);
				return $reflect->newInstanceArgs($constructorArgs);
			}
			
			return new $class();
		}
			
		return null;
	}
		
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'BorhanEventNotificationDispatchJobData' && $enumValue == self::getEmailNotificationTemplateTypeCoreValue(EmailNotificationTemplateType::EMAIL))
			return 'BorhanEmailNotificationDispatchJobData';
	
		if($baseClass == 'EventNotificationTemplate' && $enumValue == self::getEmailNotificationTemplateTypeCoreValue(EmailNotificationTemplateType::EMAIL))
			return 'EmailNotificationTemplate';
	
		if($baseClass == 'BorhanEventNotificationTemplate' && $enumValue == self::getEmailNotificationTemplateTypeCoreValue(EmailNotificationTemplateType::EMAIL))
			return 'BorhanEmailNotificationTemplate';
	
		if($baseClass == 'Form_EventNotificationTemplateConfiguration' && $enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::EMAIL)
			return 'Form_EmailNotificationTemplateConfiguration';
	
		if($baseClass == 'Borhan_Client_EventNotification_Type_EventNotificationTemplate' && $enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::EMAIL)
			return 'Borhan_Client_EmailNotification_Type_EmailNotificationTemplate';
	
		if($baseClass == 'KDispatchEventNotificationEngine' && $enumValue == BorhanEventNotificationTemplateType::EMAIL)
			return 'KDispatchEmailNotificationEngine';
			
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn() 
	{
		$minVersion = new BorhanVersion(
			self::EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR,
			self::EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR,
			self::EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD
		);
		$dependency = new BorhanDependency(self::EVENT_NOTIFICATION_PLUGIN_NAME, $minVersion);
		
		return array($dependency);
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getEmailNotificationFileSyncObjectTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('FileSyncObjectType', $value);
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getEmailNotificationTemplateTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('EventNotificationTemplateType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

<?php
/**
 * Enable event notifications on content distribution objects
 * @package plugins.contentDistributionEventNotifications
 */
class ContentDistributionEventNotificationsPlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'contentDistributionEventNotifications';
	
	const CONTENT_DISTRIBUTION_PLUGIN_NAME = 'contentDistribution';
	
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
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$eventNotificationVersion = new BorhanVersion(self::EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR, self::EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR, self::EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD);
		
		$contentDistributionDependency = new BorhanDependency(self::CONTENT_DISTRIBUTION_PLUGIN_NAME);
		$eventNotificationDependency = new BorhanDependency(self::EVENT_NOTIFICATION_PLUGIN_NAME, $eventNotificationVersion);
		
		return array($contentDistributionDependency, $eventNotificationDependency);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('ContentDistributionEventNotificationEventObjectType');
	
		if($baseEnumName == 'EventNotificationEventObjectType')
			return array('ContentDistributionEventNotificationEventObjectType');
			
		return array();
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		return null;
	}
		
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'EventNotificationEventObjectType')
		{
			if($enumValue == self::getEventNotificationEventObjectTypeCoreValue(ContentDistributionEventNotificationEventObjectType::DISTRIBUTION_PROFILE))
				return DistributionProfilePeer::OM_CLASS;
				
			if($enumValue == self::getEventNotificationEventObjectTypeCoreValue(ContentDistributionEventNotificationEventObjectType::ENTRY_DISTRIBUTION))
				return EntryDistributionPeer::OM_CLASS;
		}
					
		return null;
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getEventNotificationEventObjectTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('EventNotificationEventObjectType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

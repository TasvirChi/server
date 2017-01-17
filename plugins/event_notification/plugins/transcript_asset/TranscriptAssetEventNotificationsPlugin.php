<?php
/**
 * Enable event notifications on transcript asset objects
 * @package plugins.transcriptAssetEventNotifications
 */
class TranscriptAssetEventNotificationsPlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'transcriptAssetEventNotifications';
	
	const TRANSCRIPT_ASSET_PLUGIN_NAME = 'transcript';
	
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
		
		$transcriptAssetDependency = new BorhanDependency(self::TRANSCRIPT_ASSET_PLUGIN_NAME);
		$eventNotificationDependency = new BorhanDependency(self::EVENT_NOTIFICATION_PLUGIN_NAME, $eventNotificationVersion);
		
		return array($transcriptAssetDependency, $eventNotificationDependency);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('TranscriptAssetEventNotificationEventObjectType');
	
		if($baseEnumName == 'EventNotificationEventObjectType')
			return array('TranscriptAssetEventNotificationEventObjectType');
			
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
			if($enumValue == self::getEventNotificationEventObjectTypeCoreValue(TranscriptAssetEventNotificationEventObjectType::TRANSCRIPT_ASSET))
				return TranscriptPlugin::getObjectClass(assetPeer::OM_CLASS, TranscriptPlugin::getAssetTypeCoreValue(TranscriptAssetType::TRANSCRIPT));
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
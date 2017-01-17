<?php
/**
 * Enable event notifications on cue point objects
 * @package plugins.cuePointEventNotifications
 */
class CuePointEventNotificationsPlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'cuePointEventNotifications';
	
	const CUE_POINT_PLUGIN_NAME = 'cuePoint';
	const CUE_POINT_PLUGIN_VERSION_MAJOR = 1;
	const CUE_POINT_PLUGIN_VERSION_MINOR = 0;
	const CUE_POINT_PLUGIN_VERSION_BUILD = 0;
	
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
		$cuePointVersion = new BorhanVersion(self::CUE_POINT_PLUGIN_VERSION_MAJOR, self::CUE_POINT_PLUGIN_VERSION_MINOR, self::CUE_POINT_PLUGIN_VERSION_BUILD);
		$eventNotificationVersion = new BorhanVersion(self::EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR, self::EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR, self::EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD);
		
		$cuePointDependency = new BorhanDependency(self::CUE_POINT_PLUGIN_NAME, $cuePointVersion);
		$eventNotificationDependency = new BorhanDependency(self::EVENT_NOTIFICATION_PLUGIN_NAME, $eventNotificationVersion);
		
		return array($cuePointDependency, $eventNotificationDependency);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('CuePointEventNotificationEventObjectType');
	
		if($baseEnumName == 'EventNotificationEventObjectType')
			return array('CuePointEventNotificationEventObjectType');
			
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
		if($baseClass == 'EventNotificationEventObjectType' && $enumValue == self::getEventNotificationEventObjectTypeCoreValue(CuePointEventNotificationEventObjectType::CUE_POINT))
		{
			return 'CuePoint';
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

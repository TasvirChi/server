<?php
/**
 * Extension plugin for scheduled task plugin to add support to dispatch event notification object task
 *
 * @package plugins.scheduledTaskEventNotification
 */
class ScheduledTaskEventNotificationPlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'scheduledTaskEventNotification';
	
	const SCHEDULED_TASK_PLUGIN_NAME = 'scheduledTask';
	
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
		
		$scheduledTaskDependency = new BorhanDependency(self::SCHEDULED_TASK_PLUGIN_NAME);
		$eventNotificationDependency = new BorhanDependency(self::EVENT_NOTIFICATION_PLUGIN_NAME, $eventNotificationVersion);
		
		return array($scheduledTaskDependency, $eventNotificationDependency);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('DispatchEventNotificationObjectTaskType');
	
		if($baseEnumName == 'ObjectTaskType')
			return array('DispatchEventNotificationObjectTaskType');
			
		return array();
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if (class_exists('Borhan_Client_Client'))
			return null;

		if (class_exists('BorhanClient'))
		{
			if ($baseClass == 'KObjectTaskEntryEngineBase' && $enumValue == BorhanObjectTaskType::DISPATCH_EVENT_NOTIFICATION)
				return new KObjectTaskDispatchEventNotificationEngine();
		}
		else
		{
			$apiValue = self::getApiValue(DispatchEventNotificationObjectTaskType::DISPATCH_EVENT_NOTIFICATION);
			$dispatchEventNotificationObjectTaskCoreValue = kPluginableEnumsManager::apiToCore('ObjectTaskType', $apiValue);
			if($baseClass == 'BorhanObjectTask' && $enumValue == $dispatchEventNotificationObjectTaskCoreValue)
				return new BorhanDispatchEventNotificationObjectTask();

			if ($baseClass == 'KObjectTaskEntryEngineBase' && $enumValue == $apiValue)
				return new KObjectTaskDispatchEventNotificationEngine();
		}

		return null;
	}
		
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		return null;
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

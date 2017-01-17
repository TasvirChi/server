<?php
/**
 * Extension plugin for scheduled task plugin to add support for distributing content
 *
 * @package plugins.scheduledTaskEventNotification
 */
class ScheduledTaskContentDistributionPlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'scheduledTaskContentDistribution';
	
	const SCHEDULED_TASK_PLUGIN_NAME = 'scheduledTask';
	
	const CONTENT_DISTRIBUTION_PLUGIN_NAME = 'contentDistribution';
	const CONTENT_DISTRIBUTION_PLUGIN_VERSION_MAJOR = 1;
	const CONTENT_DISTRIBUTION_PLUGIN_VERSION_MINOR = 0;
	const CONTENT_DISTRIBUTION_PLUGIN_VERSION_BUILD = 0;
	
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
		$eventNotificationVersion = new BorhanVersion(self::CONTENT_DISTRIBUTION_PLUGIN_VERSION_MAJOR, self::CONTENT_DISTRIBUTION_PLUGIN_VERSION_MINOR, self::CONTENT_DISTRIBUTION_PLUGIN_VERSION_BUILD);
		
		$scheduledTaskDependency = new BorhanDependency(self::SCHEDULED_TASK_PLUGIN_NAME);
		$eventNotificationDependency = new BorhanDependency(self::CONTENT_DISTRIBUTION_PLUGIN_NAME, $eventNotificationVersion);
		
		return array($scheduledTaskDependency, $eventNotificationDependency);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('DistributeObjectTaskType');
	
		if($baseEnumName == 'ObjectTaskType')
			return array('DistributeObjectTaskType');
			
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
			if ($baseClass == 'KObjectTaskEntryEngineBase' && $enumValue == BorhanObjectTaskType::DISTRIBUTE)
				return new KObjectTaskDistributeEngine();
		}
		else
		{
			$apiValue = self::getApiValue(DistributeObjectTaskType::DISTRIBUTE);
			$distributeObjectTaskCoreValue = kPluginableEnumsManager::apiToCore('ObjectTaskType', $apiValue);
			if($baseClass == 'BorhanObjectTask' && $enumValue == $distributeObjectTaskCoreValue)
				return new BorhanDistributeObjectTask();

			if ($baseClass == 'KObjectTaskEntryEngineBase' && $enumValue == $apiValue)
				return new KObjectTaskDistributeEngine();
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

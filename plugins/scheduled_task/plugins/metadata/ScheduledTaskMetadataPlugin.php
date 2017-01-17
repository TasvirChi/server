<?php
/**
 * Extension plugin for scheduled task plugin to add support metadata related object tasks
 *
 * @package plugins.scheduledTaskMetadata
 */
class ScheduledTaskMetadataPlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'scheduledTaskMetadata';
	
	const SCHEDULED_TASK_PLUGIN_NAME = 'scheduledTask';
	
	const METADATA_PLUGIN_NAME = 'metadata';
	const METADATA_PLUGIN_VERSION_MAJOR = 1;
	const METADATA_PLUGIN_VERSION_MINOR = 0;
	const METADATA_PLUGIN_VERSION_BUILD = 0;
	
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
		$metadataVersion = new BorhanVersion(self::METADATA_PLUGIN_VERSION_MAJOR, self::METADATA_PLUGIN_VERSION_MINOR, self::METADATA_PLUGIN_VERSION_BUILD);
		
		$scheduledTaskDependency = new BorhanDependency(self::SCHEDULED_TASK_PLUGIN_NAME);
		$metadataDependency = new BorhanDependency(self::METADATA_PLUGIN_NAME, $metadataVersion);
		
		return array($scheduledTaskDependency, $metadataDependency);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('ExecuteMetadataXsltObjectTaskType');
	
		if($baseEnumName == 'ObjectTaskType')
			return array('ExecuteMetadataXsltObjectTaskType');
			
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
			if ($baseClass == 'KObjectTaskEntryEngineBase' && $enumValue == BorhanObjectTaskType::EXECUTE_METADATA_XSLT)
				return new KObjectTaskExecuteMetadataXsltEngine();
		}
		else
		{
			$apiValue = self::getApiValue(ExecuteMetadataXsltObjectTaskType::EXECUTE_METADATA_XSLT);
			$executeMetadataXsltObjectTaskCoreValue = kPluginableEnumsManager::apiToCore('ObjectTaskType', $apiValue);
			if($baseClass == 'BorhanObjectTask' && $enumValue == $executeMetadataXsltObjectTaskCoreValue)
				return new BorhanExecuteMetadataXsltObjectTask();

			if ($baseClass == 'KObjectTaskEntryEngineBase' && $enumValue == $apiValue)
				return new KObjectTaskExecuteMetadataXsltEngine();
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

	/* (non-PHPdoc)
 * @see IBorhanConfigurator::getConfig()
 */
	public static function getConfig($configName)
	{
		if($configName == 'generator')
			return new Zend_Config_Ini(dirname(__FILE__) . '/config/generator.ini');

		return null;
	}
}

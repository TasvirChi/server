<?php
/**
 * @package plugins.businessProcessNotification
 */
class BusinessProcessNotificationPlugin extends BorhanPlugin implements IBorhanVersion, IBorhanPending, IBorhanObjectLoader, IBorhanEnumerator, IBorhanServices, IBorhanApplicationPartialView, IBorhanAdminConsolePages, IBorhanEventConsumers, IBorhanApplicationTranslations
{
	const PLUGIN_NAME = 'businessProcessNotification';
	const PLUGIN_VERSION_MAJOR = 1;
	const PLUGIN_VERSION_MINOR = 0;
	const PLUGIN_VERSION_BUILD = 0;
	
	const EVENT_NOTIFICATION_PLUGIN_NAME = 'eventNotification';
	const EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR = 1;
	const EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR = 0;
	const EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD = 0;
	
	const BUSINESS_PROCESS_NOTIFICATION_FLOW_MANAGER = 'kBusinessProcessNotificationFlowManager';
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanVersion::getVersion()
	 */
	public static function getVersion()
	{
		return new BorhanVersion(
			self::PLUGIN_VERSION_MAJOR,
			self::PLUGIN_VERSION_MINOR,
			self::PLUGIN_VERSION_BUILD
		);		
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	 */
	public static function getEventConsumers()
	{
		return array(self::BUSINESS_PROCESS_NOTIFICATION_FLOW_MANAGER);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('BusinessProcessNotificationTemplateType');
	
		if($baseEnumName == 'EventNotificationTemplateType')
			return array('BusinessProcessNotificationTemplateType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
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
		if($baseClass == 'BorhanEventNotificationDispatchJobData')
		{
			if(
				$enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_START) || 
				$enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_SIGNAL) || 
				$enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_ABORT)
			)
				return 'BorhanBusinessProcessNotificationDispatchJobData';
		}
		
		if($baseClass == 'EventNotificationTemplate')
		{
			if($enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_START))
				return 'BusinessProcessStartNotificationTemplate';
				
			if($enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_SIGNAL))
				return 'BusinessProcessSignalNotificationTemplate';
				
			if($enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_ABORT))
				return 'BusinessProcessAbortNotificationTemplate';
		}
	
		if($baseClass == 'BorhanEventNotificationTemplate')
		{
			if($enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_START))
				return 'BorhanBusinessProcessStartNotificationTemplate';
				
			if($enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_SIGNAL))
				return 'BorhanBusinessProcessSignalNotificationTemplate';
				
			if($enumValue == self::getBusinessProcessNotificationTemplateTypeCoreValue(BusinessProcessNotificationTemplateType::BPM_ABORT))
				return 'BorhanBusinessProcessAbortNotificationTemplate';
		}
	
		if($baseClass == 'Form_EventNotificationTemplateConfiguration')
		{
			if(
				$enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::BPM_START || 
				$enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::BPM_SIGNAL || 
				$enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::BPM_ABORT
			)
				return 'Form_BusinessProcessNotificationTemplateConfiguration';
		}
	
		if($baseClass == 'Borhan_Client_EventNotification_Type_EventNotificationTemplate')
		{
			if($enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::BPM_START)
				return 'Borhan_Client_BusinessProcessNotification_Type_BusinessProcessStartNotificationTemplate';
				
			if($enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::BPM_SIGNAL)
				return 'Borhan_Client_BusinessProcessNotification_Type_BusinessProcessSignalNotificationTemplate';
				
			if($enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::BPM_ABORT)
				return 'Borhan_Client_BusinessProcessNotification_Type_BusinessProcessAbortNotificationTemplate';
		}
	
		if($baseClass == 'KDispatchEventNotificationEngine')
		{
			if(
				$enumValue == BorhanEventNotificationTemplateType::BPM_START ||
				$enumValue == BorhanEventNotificationTemplateType::BPM_SIGNAL ||
				$enumValue == BorhanEventNotificationTemplateType::BPM_ABORT
			)
				return 'KDispatchBusinessProcessNotificationEngine';
		}
			
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
	
	/* (non-PHPdoc)
	 * @see IBorhanApplicationPartialView::getApplicationPartialViews()
	 */
	public static function getApplicationPartialViews($controller, $action)
	{
		if($controller == 'plugin' && $action == 'EventNotificationTemplateConfigureAction')
		{
			return array(
				new Borhan_View_Helper_BusinessProcessNotificationTemplateConfigure(),
			);
		}
	
		if($controller == 'batch' && $action == 'entryInvestigation')
		{
			return array(
				new Borhan_View_Helper_EntryBusinessProcess(),
			);
		}
		
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanAdminConsolePages::getApplicationPages()
	 */
	public static function getApplicationPages() 
	{
		return array(
			new BusinessProcessNotificationTemplatesListProcessesAction(),
		);
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getBusinessProcessNotificationTemplateTypeCoreValue($valueName)
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

	/* (non-PHPdoc)
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap() 
	{
		return array(
			'businessProcessServer' => 'BusinessProcessServerService',
			'businessProcessCase' => 'BusinessProcessCaseService',
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanApplicationTranslations::getTranslations()
	 */
	public static function getTranslations($locale)
	{
		$array = array();
		
		$langFilePath = __DIR__ . "/config/lang/$locale.php";
		if(!file_exists($langFilePath))
		{
			$default = 'en';
			$langFilePath = __DIR__ . "/config/lang/$default.php";
		}
		
		$array = include($langFilePath);
	
		return array($locale => $array);
	}
}

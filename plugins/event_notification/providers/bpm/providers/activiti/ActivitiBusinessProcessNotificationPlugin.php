<?php
/**
 * @package plugins.activitiBusinessProcessNotification
 */
class ActivitiBusinessProcessNotificationPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanPending, IBorhanObjectLoader, IBorhanEnumerator
{
	const PLUGIN_NAME = 'activitiBusinessProcessNotification';
	
	const BPM_NOTIFICATION_PLUGIN_NAME = 'businessProcessNotification';
	const BPM_NOTIFICATION_PLUGIN_VERSION_MAJOR = 1;
	const BPM_NOTIFICATION_PLUGIN_VERSION_MINOR = 0;
	const BPM_NOTIFICATION_PLUGIN_VERSION_BUILD = 0;
	
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
			return array('ActivitiBusinessProcessProvider');
	
		if($baseEnumName == 'BusinessProcessProvider')
			return array('ActivitiBusinessProcessProvider');
			
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
		if($baseClass == 'kBusinessProcessProvider')
		{
			if(class_exists('BorhanClient') && defined('BorhanBusinessProcessProvider::ACTIVITI'))
			{
				if($enumValue == BorhanBusinessProcessProvider::ACTIVITI)
					return 'kActivitiBusinessProcessProvider';
			}
			elseif(class_exists('Borhan_Client_Client') && defined('Borhan_Client_BusinessProcessNotification_Enum_BusinessProcessProvider::ACTIVITI'))
			{
				if($enumValue == Borhan_Client_BusinessProcessNotification_Enum_BusinessProcessProvider::ACTIVITI)
					return 'kActivitiBusinessProcessProvider';
			}
			elseif($enumValue == self::getApiValue(ActivitiBusinessProcessProvider::ACTIVITI))
			{
				return 'kActivitiBusinessProcessProvider';
			}
		}
			
		if($baseClass == 'BusinessProcessServer' && $enumValue == self::getActivitiBusinessProcessProviderCoreValue(ActivitiBusinessProcessProvider::ACTIVITI))
			return 'ActivitiBusinessProcessServer';
			
		if($baseClass == 'BorhanBusinessProcessServer' && $enumValue == self::getActivitiBusinessProcessProviderCoreValue(ActivitiBusinessProcessProvider::ACTIVITI))
			return 'BorhanActivitiBusinessProcessServer';
					
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn() 
	{
		$minVersion = new BorhanVersion(
			self::BPM_NOTIFICATION_PLUGIN_VERSION_MAJOR,
			self::BPM_NOTIFICATION_PLUGIN_VERSION_MINOR,
			self::BPM_NOTIFICATION_PLUGIN_VERSION_BUILD
		);
		$dependency = new BorhanDependency(self::BPM_NOTIFICATION_PLUGIN_NAME, $minVersion);
		
		return array($dependency);
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getActivitiBusinessProcessProviderCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('BusinessProcessProvider', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

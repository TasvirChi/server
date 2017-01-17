<?php
/**
 * @package plugins.integration
 */
class IntegrationPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanServices, IBorhanEventConsumers, IBorhanEnumerator, IBorhanVersion, IBorhanPending, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'integration';
	const PLUGIN_VERSION_MAJOR = 1;
	const PLUGIN_VERSION_MINOR = 0;
	const PLUGIN_VERSION_BUILD = 0;
	const INTEGRATION_FLOW_MANAGER = 'kIntegrationFlowManager';

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
		$dependency = new BorhanDependency(MetadataPlugin::getPluginName());
		return array($dependency);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId)
	{
		$partner = PartnerPeer::retrieveByPK($partnerId);
		if(!$partner)
			return false;
			
		return $partner->getPluginEnabled(self::PLUGIN_NAME);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap()
	{
		$map = array(
			'integration' => 'IntegrationService',
		);
		return $map;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	 */
	public static function getEventConsumers()
	{
		return array(
			self::INTEGRATION_FLOW_MANAGER,
		);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('IntegrationBatchJobType');
	
		if($baseEnumName == 'BatchJobType')
			return array('IntegrationBatchJobType');
			
		return array();
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
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{			
		$objectClass = self::getObjectClass($baseClass, $enumValue);
		if (is_null($objectClass)) 
		{
			return null;
		}
		
		if (!is_null($constructorArgs))
		{
			$reflect = new ReflectionClass($objectClass);
			return $reflect->newInstanceArgs($constructorArgs);
		}
		else
		{
			return new $objectClass();
		}
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'kJobData')
		{
			if($enumValue == self::getBatchJobTypeCoreValue(IntegrationBatchJobType::INTEGRATION))
				return 'kIntegrationJobData';
		}
	
		if($baseClass == 'BorhanJobData')
		{
			if($enumValue == self::getApiValue(IntegrationBatchJobType::INTEGRATION) || $enumValue == self::getBatchJobTypeCoreValue(IntegrationBatchJobType::INTEGRATION))
				return 'BorhanIntegrationJobData';
		}		
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getBatchJobTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('BatchJobType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

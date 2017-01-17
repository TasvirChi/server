<?php
/**
 * @package plugins.avidemux
 */
class AvidemuxPlugin extends BorhanPlugin implements IBorhanObjectLoader, IBorhanEnumerator
{
	const PLUGIN_NAME = 'avidemux';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/**
	 * @param BorhanPluginManager::OBJECT_TYPE $objectType
	 * @param string $enumValue
	 * @param array $constructorArgs
	 * @return object
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::AVIDEMUX)
		{
			if(!isset($constructorArgs['params']) || !isset($constructorArgs['outFilePath']))
				return null;
				
			$params = $constructorArgs['params'];
			return new KOperationEngineAvidemux($params->avidemuxCmd, $constructorArgs['outFilePath']);
		}
			
		if($baseClass == 'KDLOperatorBase' && $enumValue == self::getApiValue(AvidemuxConversionEngineType::AVIDEMUX))
		{
			return new KDLOperatorAvidemux($enumValue);
		}
		
		return null;
	}

	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @return string
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'KOperationEngine' && $enumValue == self::getApiValue(AvidemuxConversionEngineType::AVIDEMUX))
			return 'KOperationEngineAvidemux';
	
		if($baseClass == 'KDLOperatorBase' && $enumValue == self::getConversionEngineCoreValue(AvidemuxConversionEngineType::AVIDEMUX))
			return 'KDLOperatorAvidemux';
		
		return null;
	}
	
	/**
	 * @return array<string> list of enum classes names that extend the base enum name
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('AvidemuxConversionEngineType');
	
		if($baseEnumName == 'conversionEngineType')
			return array('AvidemuxConversionEngineType');
			
		return array();
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getConversionEngineCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('conversionEngineType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

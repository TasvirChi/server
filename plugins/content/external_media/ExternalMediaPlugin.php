<?php
/**
 * @package plugins.externalMedia
 */
class ExternalMediaPlugin extends BorhanPlugin implements IBorhanServices, IBorhanObjectLoader, IBorhanEnumerator, IBorhanTypeExtender, IBorhanSearchDataContributor, IBorhanEventConsumers
{
	const PLUGIN_NAME = 'externalMedia';
	const EXTERNAL_MEDIA_CREATED_HANDLER = 'ExternalMediaCreatedHandler';
	const SEARCH_DATA_SUFFIX = 's';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	 */
	public static function getEventConsumers()
	{
		return array(
			self::EXTERNAL_MEDIA_CREATED_HANDLER,
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanTypeExtender::getExtendedTypes()
	 */
	public static function getExtendedTypes($baseClass, $enumValue)
	{
		if($baseClass == entryPeer::OM_CLASS && $enumValue == entryType::MEDIA_CLIP)
		{
			return array(
				ExternalMediaPlugin::getEntryTypeCoreValue(ExternalMediaEntryType::EXTERNAL_MEDIA),
			);
		}
		
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		$class = self::getObjectClass($baseClass, $enumValue);
		if($class)
			return new $class();
		
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'entry' && $enumValue == ExternalMediaPlugin::getEntryTypeCoreValue(ExternalMediaEntryType::EXTERNAL_MEDIA))
		{
			return 'ExternalMediaEntry';
		}
		
		if($baseClass == 'BorhanBaseEntry' && $enumValue == ExternalMediaPlugin::getEntryTypeCoreValue(ExternalMediaEntryType::EXTERNAL_MEDIA))
		{
			return 'BorhanExternalMediaEntry';
		}
		
		return null;
	}

	/**
	 * @return array<string,string> in the form array[serviceName] = serviceClass
	 */
	public static function getServicesMap()
	{
		$map = array(
			'externalMedia' => 'ExternalMediaService',
		);
		return $map;
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getEntryTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('entryType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
	
	/**
	 * @return array<string> list of enum classes names that extend the base enum name
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('ExternalMediaEntryType');
	
		if($baseEnumName == 'entryType')
			return array('ExternalMediaEntryType');
			
		return array();
	}

	public static function getExternalSourceSearchData($externalSourceType)
	{
		return self::getPluginName() . $externalSourceType . self::SEARCH_DATA_SUFFIX;
	}

	/* (non-PHPdoc)
	 * @see IBorhanSearchDataContributor::getSearchData()
	 */
	public static function getSearchData(BaseObject $object)
	{
		if($object instanceof ExternalMediaEntry)
		{
			return array('plugins_data' => self::getExternalSourceSearchData($object->getExternalSourceType()));
		}
			
		return null;
	}
}

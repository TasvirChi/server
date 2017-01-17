<?php
/**
 * Enable serving live conversion profile to the Wowza servers as XML
 * @package plugins.wowza
 */
class WowzaPlugin extends BorhanPlugin implements IBorhanVersion, IBorhanServices, IBorhanObjectLoader, IBorhanEnumerator
{
	const PLUGIN_NAME = 'wowza';
	
	const PLUGIN_VERSION_MAJOR = 1;
	const PLUGIN_VERSION_MINOR = 0;
	const PLUGIN_VERSION_BUILD = 0;
	
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
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap()
	{
		$map = array(
			'liveConversionProfile' => 'LiveConversionProfileService',
		);
		return $map;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('WowzaMediaServerNodeType');
	
		if($baseEnumName == 'serverNodeType')
			return array('WowzaMediaServerNodeType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'BorhanServerNode' && $enumValue == self::getWowzaMediaServerTypeCoreValue(WowzaMediaServerNodeType::WOWZA_MEDIA_SERVER))
			return new BorhanWowzaMediaServerNode();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'ServerNode' && $enumValue == self::getWowzaMediaServerTypeCoreValue(WowzaMediaServerNodeType::WOWZA_MEDIA_SERVER))
			return 'WowzaMediaServerNode';
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCuePoint::getCuePointTypeCoreValue()
	 */
	public static function getWowzaMediaServerTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('serverNodeType', $value);
	}
}

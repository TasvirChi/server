<?php
/**
 * Enable event cue point objects management on entry objects
 * @package plugins.EventCuePoint
 */
class EventCuePointPlugin extends BorhanPlugin implements IBorhanCuePoint, IBorhanEventConsumers
{
	const PLUGIN_NAME = 'eventCuePoint';
	const CUE_POINT_VERSION_MAJOR = 1;
	const CUE_POINT_VERSION_MINOR = 0;
	const CUE_POINT_VERSION_BUILD = 0;
	const CUE_POINT_NAME = 'cuePoint';
	
	const EVENT_CUE_POINT_CONSUMER = 'kEventCuePointConsumer';
	
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
		return true;
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('EventCuePointType');
	
		if($baseEnumName == 'CuePointType')
			return array('EventCuePointType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$cuePointVersion = new BorhanVersion(
			self::CUE_POINT_VERSION_MAJOR,
			self::CUE_POINT_VERSION_MINOR,
			self::CUE_POINT_VERSION_BUILD);
			
		$dependency = new BorhanDependency(self::CUE_POINT_NAME, $cuePointVersion);
		return array($dependency);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'BorhanCuePoint' && $enumValue == self::getCuePointTypeCoreValue(EventCuePointType::EVENT))
			return new BorhanEventCuePoint();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'CuePoint' && $enumValue == self::getCuePointTypeCoreValue(EventCuePointType::EVENT))
			return 'EventCuePoint';
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCuePoint::getCuePointTypeCoreValue()
	 */
	public static function getCuePointTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('CuePointType', $value);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCuePoint::getApiValue()
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
	
	public static function contributeToSchema($type)
	{
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	*/
	public static function getEventConsumers()
	{
		return array(
				self::EVENT_CUE_POINT_CONSUMER
		);
	}
	
	public static function getTypesToIndexOnEntry()
	{
		return array();
	}

	public static function shouldCloneByProperty(entry $entry)
	{
		return false;
	}
}

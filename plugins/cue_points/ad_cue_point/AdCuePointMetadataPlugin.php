<?php
/**
 * Enable custom metadata on ad cue point objects
 * @package plugins.adCuePoint
 */
class AdCuePointMetadataPlugin extends BorhanPlugin implements IBorhanPending, IBorhanObjectLoader, IBorhanCuePointXmlParser, IBorhanEnumerator
{
	const PLUGIN_NAME = 'adCuePointMetadata';
	const METADATA_BULK_UPLOAD_XML_PLUGIN_NAME = 'metadataBulkUploadXml';

	/* (non-PHPdoc)
	 * @see BorhanPlugin::getInstance()
	 */
	public function getInstance($interface)
	{
		if($this instanceof $interface)
			return $this;
			
		if($interface == 'IBorhanBulkUploadXmlHandler')
			return new MetadataBulkUploadXmlEngineHandler(BorhanMetadataObjectType::AD_CUE_POINT, 'BorhanAdCuePoint', 'scene-customData');
			
		return null;
	}
	
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
		$cuePointMetadataDependency = new BorhanDependency(CuePointMetadataPlugin::getPluginName());
		$metadataBulkUploadXmlDependency = new BorhanDependency(self::METADATA_BULK_UPLOAD_XML_PLUGIN_NAME);
		
		return array($cuePointMetadataDependency, $metadataBulkUploadXmlDependency);
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('AdCuePointMetadataObjectType');
	
		if($baseEnumName == 'MetadataObjectType')
			return array('AdCuePointMetadataObjectType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		$class = self::getObjectClass($baseClass, $enumValue);
		if($class && class_exists($class))
			return new $class();
			
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'IMetadataPeer' && $enumValue == self::getMetadataObjectTypeCoreValue(AdCuePointMetadataObjectType::AD_CUE_POINT))
			return 'CuePointPeer';
			
		if($baseClass == 'IMetadataObject' && $enumValue == self::getMetadataObjectTypeCoreValue(AdCuePointMetadataObjectType::AD_CUE_POINT))
			return 'AdCuePoint';
	}

	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getMetadataObjectTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('MetadataObjectType', $value);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCuePointXmlParser::parseXml()
	 */
	public static function parseXml(SimpleXMLElement $scene, $partnerId, CuePoint $cuePoint = null)
	{
		if(is_null($cuePoint) || $scene->getName() != 'scene-ad-cue-point' || !($cuePoint instanceof AdCuePoint))
			return $cuePoint;
			
		$objectType = self::getMetadataObjectTypeCoreValue(AdCuePointMetadataObjectType::AD_CUE_POINT);
		return CuePointMetadataPlugin::parseXml($objectType, $scene, $partnerId, $cuePoint);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCuePointXmlParser::generateXml()
	 */
	public static function generateXml(CuePoint $cuePoint, SimpleXMLElement $scenes, SimpleXMLElement $scene = null)
	{
		if(is_null($scene) || $scene->getName() != 'scene-ad-cue-point' || !($cuePoint instanceof AdCuePoint))
			return $scene;
			
		$objectType = self::getMetadataObjectTypeCoreValue(AdCuePointMetadataObjectType::AD_CUE_POINT);
		return CuePointMetadataPlugin::generateCuePointXml($scene, $objectType, $cuePoint->getId());
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCuePointXmlParser::syndicate()
	 */
	public static function syndicate(CuePoint $cuePoint, SimpleXMLElement $scenes, SimpleXMLElement $scene = null)
	{
		self::generateXml($cuePoint, $scenes, $scene);
	}
}

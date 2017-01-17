<?php
/**
 * @package plugins.metadataBulkUploadXml
 */
class MetadataBulkUploadXmlEnginePlugin extends BorhanPlugin implements IBorhanPending, IBorhanConfigurator
{
	const PLUGIN_NAME = 'metadataBulkUploadXmlEngine';
	
	const BULK_UPLOAD_XML_VERSION_MAJOR = 1;
	const BULK_UPLOAD_XML_VERSION_MINOR = 0;
	const BULK_UPLOAD_XML_VERSION_BUILD = 0;
	
	/* (non-PHPdoc)
	 * @see BorhanPlugin::getInstance()
	 */
	public function getInstance($interface)
	{
		if($this instanceof $interface)
			return $this;
			
		if($interface == 'IBorhanBulkUploadXmlHandler')
			return new MetadataBulkUploadXmlEngineHandler(BorhanMetadataObjectType::ENTRY, 'BorhanBaseEntry', 'customData', 'customDataItems');
			
		return null;
	}
	
	/**
	 * @return string
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
		$bulkUploadXmlVersion = new BorhanVersion(
			self::BULK_UPLOAD_XML_VERSION_MAJOR,
			self::BULK_UPLOAD_XML_VERSION_MINOR,
			self::BULK_UPLOAD_XML_VERSION_BUILD);
			
		$bulkUploadXmlDependency = new BorhanDependency(BulkUploadXmlPlugin::getPluginName(), $bulkUploadXmlVersion);
		$metadataDependency = new BorhanDependency(MetadataPlugin::getPluginName());
		
		return array($bulkUploadXmlDependency, $metadataDependency);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanConfigurator::getConfig()
	 */
	public static function getConfig($configName)
	{
		if($configName == 'generator')
			return new Zend_Config_Ini(dirname(__FILE__) . '/config/metadataBulkUploadXml.generator.ini');
			
		return null;
	}
}

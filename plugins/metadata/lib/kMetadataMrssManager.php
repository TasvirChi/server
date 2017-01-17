<?php
/**
 * @package plugins.metadata
 * @subpackage lib
 */
class kMetadataMrssManager implements IBorhanMrssContributor
{
	/**
	 * @var kMetadataMrssManager
	 */
	protected static $instance;
	
	protected function __construct()
	{
	}
	
	/**
	 * @return kMetadataMrssManager
	 */
	public static function get()
	{
		if(!self::$instance)
			self::$instance = new kMetadataMrssManager();
			
		return self::$instance;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanMrssContributor::contributeToSchema()
	 */
	public function contribute(BaseObject $object, SimpleXMLElement $mrss, kMrssParameters $mrssParams = null)
	{
		$objectType = kMetadataManager::getTypeNameFromObject($object);
		$metadatas = MetadataPeer::retrieveAllByObject($objectType, $object->getId());
		foreach($metadatas as $metadata)
			$this->contributeMetadata($metadata, $mrss, $mrssParams);
	}
	
	/**
	 * @param Metadata $metadata
	 * @param SimpleXMLElement $mrss
	 * @param kMrssParameters $mrssParams
	 * @return SimpleXMLElement
	 */
	public function contributeMetadata(Metadata $metadata, SimpleXMLElement $mrss, kMrssParameters $mrssParams = null)
	{
		$key = $metadata->getSyncKey(Metadata::FILE_SYNC_METADATA_DATA);
		$xml = kFileSyncUtils::file_get_contents($key, true, false);
		if (is_null($xml)){
			BorhanLog::alert("ready file sync was not found for key[$key]");
			return;
		}
		$metadataXml = new SimpleXMLElement($xml);
		
		$customData = $mrss->addChild('customData');
		$customData->addAttribute('metadataId', $metadata->getId());
		$customData->addAttribute('metadataVersion', $metadata->getVersion());
		$customData->addAttribute('metadataProfileId', $metadata->getMetadataProfileId());
		$customData->addAttribute('metadataProfileVersion', $metadata->getMetadataProfileVersion());
		
		$this->contributeMetadataObject($customData, $metadataXml, $mrssParams, '');
	}
	
	/**
	 * @param SimpleXMLElement $mrss
	 * @param SimpleXMLElement $metadata
	 * @param kMrssParameters $mrssParams
	 * @return SimpleXMLElement
	 */
	public function contributeMetadataObject(SimpleXMLElement $mrss, SimpleXMLElement $metadata, kMrssParameters $mrssParams = null, $currentXPath)
	{
		$currentXPath .= "/*[local-name()='" . $metadata->getName() . "']";
		
		$metadataObject = $mrss->addChild($metadata->getName());
		foreach($metadata->attributes() as $attributeField => $attributeValue)
			$metadataObject->addAttribute($attributeField, $attributeValue);

		foreach($metadata as $metadataField => $metadataValue)
		{
			if($metadataValue instanceof SimpleXMLElement && count($metadataValue))
			{
				$this->contributeMetadataObject($metadataObject, $metadataValue, $mrssParams, $currentXPath);
			}
			else
			{
				$metadataObject->addChild($metadataField, kString::stringToSafeXml($metadataValue));
			}					
		}				
	}

	/* (non-PHPdoc)
	 * @see IBorhanBase::getInstance()
	 */
	public function getInstance($interface)
	{
		if($this instanceof $interface)
			return $this;
			
		$plugin = BorhanPluginManager::getPluginInstance(MetadataPlugin::getPluginName());		
		if($plugin)
			return $plugin->getInstance($interface);
		
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanMrssContributor::returnObjectFeatureType()
	 */
	public function getObjectFeatureType() 
	{
		return MetadataPlugin::getObjectFeaturetTypeCoreValue(MetadataObjectFeatureType::CUSTOM_DATA);
	}

	
}
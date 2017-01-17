<?php
/**
 * Enable indexing and searching caption asset objects
 * @package plugins.captionSearch
 */
class CaptionSearchPlugin extends BorhanPlugin implements IBorhanPending, IBorhanPermissions, IBorhanServices, IBorhanEventConsumers, IBorhanEnumerator, IBorhanObjectLoader, IBorhanSearchDataContributor
{
	const MAX_CAPTION_FILE_SIZE_FOR_INDEXING = 900000; // limit the size of text which can indexed, the mysql packet size is limited by default to 1M anyway
	const PLUGIN_NAME = 'captionSearch';
	const INDEX_NAME = 'caption_item';
	const SEARCH_FIELD_DATA = 'data';
	const SEARCH_TEXT_SUFFIX = 'csend';
	
	const CAPTION_SEARCH_FLOW_MANAGER_CLASS = 'kCaptionSearchFlowManager';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$captionDependency = new BorhanDependency(CaptionPlugin::getPluginName());
		
		return array($captionDependency);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId)
	{
		if($partnerId == Partner::BATCH_PARTNER_ID)
			return true;
			
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
			'captionAssetItem' => 'CaptionAssetItemService',
		);
		return $map;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	 */
	public static function getEventConsumers()
	{
		return array(
			self::CAPTION_SEARCH_FLOW_MANAGER_CLASS,
		);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('CaptionSearchBatchJobType');
			
		if($baseEnumName == 'BatchJobType')
			return array('CaptionSearchBatchJobType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'kJobData' && $enumValue == self::getBatchJobTypeCoreValue(CaptionSearchBatchJobType::PARSE_CAPTION_ASSET))
			return new kParseCaptionAssetJobData();
	
		if($baseClass == 'BorhanJobData' && $enumValue == self::getApiValue(CaptionSearchBatchJobType::PARSE_CAPTION_ASSET))
			return new BorhanParseCaptionAssetJobData();
		
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'kJobData' && $enumValue == self::getBatchJobTypeCoreValue(CaptionSearchBatchJobType::PARSE_CAPTION_ASSET))
			return 'kParseCaptionAssetJobData';
	
		if($baseClass == 'BorhanJobData' && $enumValue == self::getApiValue(CaptionSearchBatchJobType::PARSE_CAPTION_ASSET))
			return 'BorhanParseCaptionAssetJobData';
		
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanSearchDataContributor::getSearchData()
	 */
	public static function getSearchData(BaseObject $object)
	{
		if($object instanceof entry && self::isAllowedPartner($object->getPartnerId()))
			return self::getCaptionSearchData($object);
			
		return null;
	}
	
	public static function getCaptionSearchData(entry $entry)
	{
		$captionAssets = assetPeer::retrieveByEntryId($entry->getId(), array(CaptionPlugin::getAssetTypeCoreValue(CaptionAssetType::CAPTION)));
		if(!$captionAssets || !count($captionAssets))
			return null;
			
		$data = array();
		foreach($captionAssets as $captionAsset)
		{
			/* @var $captionAsset CaptionAsset */
			
			$syncKey = $captionAsset->getSyncKey(asset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
			$content = kFileSyncUtils::file_get_contents($syncKey, true, false, self::MAX_CAPTION_FILE_SIZE_FOR_INDEXING);
			if(!$content)
				continue;
				
	    	$captionsContentManager = kCaptionsContentManager::getCoreContentManager($captionAsset->getContainerFormat());
	    	if(!$captionsContentManager)
	    	{
	    		BorhanLog::err("Captions content manager not found for format [" . $captionAsset->getContainerFormat() . "]");
	    		continue;
	    	}
	    		
	    	$content = $captionsContentManager->getContent($content);
	    	if(!$content)
	    		continue;
	    	
			$data[] = $captionAsset->getId() . " ca_prefix $content ca_sufix";
		}
		
		$dataField = CaptionSearchPlugin::getSearchFieldName(CaptionSearchPlugin::SEARCH_FIELD_DATA);
		$searchValues = array(
			$dataField => CaptionSearchPlugin::PLUGIN_NAME . ' ' . implode(' ', $data) . ' ' . CaptionSearchPlugin::SEARCH_TEXT_SUFFIX
		);
		
		return $searchValues;
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
	
	/**
	 * return field name as appears in index schema
	 * @param string $fieldName
	 */
	public static function getSearchFieldName($fieldName){
		if ($fieldName == self::SEARCH_FIELD_DATA)
			return  'plugins_data';
			
		return CaptionPlugin::getPluginName() . '_' . $fieldName;
	}
}

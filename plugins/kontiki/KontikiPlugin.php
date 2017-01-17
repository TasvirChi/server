<?php
/**
 * Enable upload and playback of content to and from Kontiki ECDN
 * @package plugins.kontiki
 */
class KontikiPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanEnumerator, IBorhanObjectLoader , IBorhanEventConsumers, IBorhanContextDataHelper
{
	const PLUGIN_NAME = 'kontiki';
    
    const KONTIKI_ASSET_TAG = 'kontiki';
	
	const SERVICE_TOKEN_PREFIX = 'srv-';
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getDeliveryProfileType($valueName)
	{
		$apiValue = self::getApiValue($valueName);
		return kPluginableEnumsManager::apiToCore('DeliveryProfileType', $apiValue);
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if ($baseClass == 'KExportEngine')
		{
			if ($enumValue == BorhanStorageProfileProtocol::KONTIKI)
			{
				list($data, $partnerId) = $constructorArgs;
				return new KKontikiExportEngine($data, $partnerId);
			}
		}
		if ($baseClass == 'kStorageExportJobData')
		{
            if ($enumValue == self::getStorageProfileProtocolCoreValue(KontikiStorageProfileProtocol::KONTIKI))
            {
                return new kKontikiStorageExportJobData();
            }
		}
        if ($baseClass == 'kStorageDeleteJobData')
        {
            if ($enumValue == self::getStorageProfileProtocolCoreValue(KontikiStorageProfileProtocol::KONTIKI))
            {
                return new kKontikiStorageDeleteJobData();
            }
        }
		if ($baseClass == 'BorhanJobData')
		{
			$jobSubType = $constructorArgs["coreJobSubType"];
			if ($jobSubType == self::getStorageProfileProtocolCoreValue(KontikiStorageProfileProtocol::KONTIKI))
			{
				if ($enumValue == BatchJobType::STORAGE_EXPORT)
				{
					return new BorhanKontikiStorageExportJobData();
				}
			 	if ($enumValue == BatchJobType::STORAGE_DELETE)
	            {
	                return new BorhanKontikiStorageDeleteJobData();
	            }
			}
        }
		if ($baseClass == 'BorhanStorageProfile')
        {
            if ($enumValue == self::getStorageProfileProtocolCoreValue(KontikiStorageProfileProtocol::KONTIKI))
            {
                return new BorhanKontikiStorageProfile();
            }
        }
		if ($baseClass =='Form_Partner_BaseStorageConfiguration' && $enumValue == Borhan_Client_Enum_StorageProfileProtocol::KONTIKI)
		{
			return new Form_KontikiStorageConfiguration();
		}
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue) {
		if($baseClass == 'StorageProfile' && $enumValue == self::getStorageProfileProtocolCoreValue(KontikiStorageProfileProtocol::KONTIKI))
            return 'KontikiStorageProfile';
		
		if ($baseClass == 'Borhan_Client_Type_StorageProfile' && $enumValue == Borhan_Client_Enum_StorageProfileProtocol::KONTIKI)
			return 'Borhan_Client_Kontiki_Type_KontikiStorageProfile';
		
		if ($baseClass == 'DeliveryProfile') {
			if($enumValue == self::getDeliveryProfileType(KontikiDeliveryProfileType::KONTIKI_HTTP))
				return 'DeliveryProfileKontikiHttp';
		}
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null) {
		if (!$baseEnumName)
		{
			return array('KontikiStorageProfileProtocol', 'KontikiDeliveryProfileType');
		}
		if ($baseEnumName == 'StorageProfileProtocol')
		{
			return array('KontikiStorageProfileProtocol');
		}
		if($baseEnumName == 'DeliveryProfileType')
			return array('KontikiDeliveryProfileType');

		return array();

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
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName() {
		return self::PLUGIN_NAME;

	}

	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}

	public static function getStorageProfileProtocolCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('StorageProfileProtocol', $value);
	}

	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	 */
	public static function getEventConsumers()
	{
        return array ('kKontikiManager');
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanContextDataHelper::getContextDataStreamerType()
	 */
	public function getContextDataStreamerType (accessControlScope $scope, $flavorTags, $streamerType)
	{
		$tagsArray = explode(',', $flavorTags);
		if ($tagsArray[0] == self::KONTIKI_ASSET_TAG)
		{
			return PlaybackProtocol::HTTP;
		}
		
		return $streamerType;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanContextDataHelper::getContextDataMediaProtocol()
	 */
	public function getContextDataMediaProtocol (accessControlScope $scope, $flavorTags, $streamerType, $mediaProtocol)
	{
		$tagsArray = explode(',', $flavorTags);
		if ($tagsArray[0] == self::KONTIKI_ASSET_TAG)
		{
			return PlaybackProtocol::HTTP;
		}
		
		return $mediaProtocol;
	}
}
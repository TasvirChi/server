<?php
/**
 * @package plugins.playReady
 */
class PlayReadyPlugin extends BorhanPlugin implements IBorhanEnumerator, IBorhanServices , IBorhanPermissionsEnabler, IBorhanObjectLoader, IBorhanSearchDataContributor, IBorhanPending, IBorhanApplicationPartialView, IBorhanEventConsumers, IBorhanPlaybackContextDataContributor
{
	const PLUGIN_NAME = 'playReady';
	const SEARCH_DATA_SUFFIX = 's';
	const PLAY_READY_EVENTS_CONSUMER = 'kPlayReadyEventsConsumer';
	
	const ENTRY_CUSTOM_DATA_PLAY_READY_KEY_ID = 'play_ready_key_id';
	const PLAY_READY_TAG = 'playready';
	
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
		$drmDependency = new BorhanDependency(DrmPlugin::getPluginName());
		
		return array($drmDependency);
	}
			
	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{	
		if(is_null($baseEnumName))
			return array('PlayReadyLicenseScenario', 'PlayReadyLicenseType', 'PlayReadyProviderType', 'PlayReadySchemeName');
		if($baseEnumName == 'DrmLicenseScenario')
			return array('PlayReadyLicenseScenario');
		if($baseEnumName == 'DrmLicenseType')
			return array('PlayReadyLicenseType');
		if($baseEnumName == 'DrmProviderType')
			return array('PlayReadyProviderType');
		if ($baseEnumName == 'DrmSchemeName')
			return array('PlayReadySchemeName');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'BorhanDrmProfile' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return new BorhanPlayReadyProfile();
		if($baseClass == 'BorhanDrmProfile' && $enumValue == self::getApiValue(PlayReadyProviderType::PLAY_READY))
			return new BorhanPlayReadyProfile();		
	
		if($baseClass == 'BorhanDrmPolicy' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return new BorhanPlayReadyPolicy();
		
		if($baseClass == 'DrmProfile' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return new PlayReadyProfile();
			
		if($baseClass == 'DrmPolicy' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return new PlayReadyPolicy();
			
		if (class_exists('Borhan_Client_Client'))
		{
			if ($baseClass == 'Borhan_Client_Drm_Type_DrmProfile' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::PLAY_READY)
    		{
    			return new Borhan_Client_PlayReady_Type_PlayReadyProfile();
    		}
    		if ($baseClass == 'Form_DrmProfileConfigureExtend_SubForm' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::PLAY_READY)
    		{
     			return new Form_PlayReadyProfileConfigureExtend_SubForm();
    		}	   		
    		
		}
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{	
		if($baseClass == 'BorhanDrmProfile' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return 'BorhanPlayReadyProfile';
		if($baseClass == 'BorhanDrmProfile' && $enumValue == self::getApiValue(PlayReadyProviderType::PLAY_READY))
			return 'BorhanPlayReadyProfile';		
			
		if($baseClass == 'BorhanDrmPolicy' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return 'BorhanPlayReadyPolicy';
		
		if($baseClass == 'DrmProfile' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return 'PlayReadyProfile';
			
		if($baseClass == 'DrmPolicy' && $enumValue == PlayReadyPlugin::getPlayReadyProviderCoreValue())
			return 'PlayReadyPolicy';
			
		if (class_exists('Borhan_Client_Client'))
		{
			if ($baseClass == 'Borhan_Client_Drm_Type_DrmProfile' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::PLAY_READY)
    		{
    			return 'Borhan_Client_PlayReady_Type_PlayReadyProfile';
    		}
    		if ($baseClass == 'Form_DrmProfileConfigureExtend_SubForm' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::PLAY_READY)
    		{
     			return 'Form_PlayReadyProfileConfigureExtend_SubForm';
    		}	   		
    		
		}
			
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanApplicationPartialView::getApplicationPartialViews()
	 */
	public static function getApplicationPartialViews($controller, $action)
	{
		if($controller == 'plugin' && $action == 'DrmProfileConfigureAction')
		{
			return array(
				new Borhan_View_Helper_PlayReadyProfileConfigure(),
			);
		}
		
		return array();
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
	public static function getCoreValue($type, $valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore($type, $value);
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getPlayReadyProviderCoreValue()
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . PlayReadyProviderType::PLAY_READY;
		return kPluginableEnumsManager::apiToCore('DrmProviderType', $value);
	}

	/* (non-PHPdoc)
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap() {
		$map = array(
			'playReadyDrm' => 'PlayReadyDrmService',
		);
		return $map;	
	}

	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId) {	
		if (in_array($partnerId, array(Partner::ADMIN_CONSOLE_PARTNER_ID, Partner::BATCH_PARTNER_ID)))
			return true;		
		$partner = PartnerPeer::retrieveByPK($partnerId);
		if(!$partner)
			return false;
		return $partner->getPluginEnabled(self::PLUGIN_NAME);			
	}

	/**
	 * @return array
	 */
	public static function getEventConsumers()
	{
		return array(
			self::PLAY_READY_EVENTS_CONSUMER,
		);
	}
	
	public static function getPlayReadyKeyIdSearchData($keyId)
	{
		return self::getPluginName() . $keyId . self::SEARCH_DATA_SUFFIX;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanSearchDataContributor::getSearchData()
	 */
	public static function getSearchData(BaseObject $object)
	{
		if($object instanceof entry)
		{
			$keyId = $object->getFromCustomData(self::ENTRY_CUSTOM_DATA_PLAY_READY_KEY_ID);
			if($keyId)
			{
				$searchData = self::getPlayReadyKeyIdSearchData($keyId);			
				return array('plugins_data' => $searchData);
			}
		}
			
		return null;
	}
	
	public static function getPlayReadyConfigParam($key)
	{
		return DrmPlugin::getConfigParam(self::PLUGIN_NAME, $key);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPermissionsEnabler::permissionEnabled()
	 */
	public static function permissionEnabled($partnerId, $permissionName) 
	{
		if($permissionName == 'PLAYREADY_PLUGIN_PERMISSION')
			kPlayReadyPartnerSetup::setupPartner($partnerId);
		
	}

    public function contributeToPlaybackContextDataResult(entry $entry, kPlaybackContextDataParams $entryPlayingDataParams, kPlaybackContextDataResult $result, kContextDataHelper $contextDataHelper)
	{
		if ($this->shouldContribute($entry) && $this->isSupportStreamerTypes($entryPlayingDataParams->getDeliveryProfile()->getStreamerType()) )
		{
			$playReadyProfile = DrmProfilePeer::retrieveByProviderAndPartnerID(PlayReadyPlugin::getPlayReadyProviderCoreValue(), kCurrentContext::getCurrentPartnerId());
			if ($playReadyProfile)
			{
				/* @var PlayReadyProfile $playReadyProfile */

				$signingKey = kConf::get('signing_key', 'drm', null);
				if ($signingKey)
				{
					$customDataJson = DrmLicenseUtils::createCustomDataForEntry($entry->getId(), $entryPlayingDataParams->getFlavors(), $signingKey);
					$customDataObject = reset($customDataJson);
					$data = new kDrmPlaybackPluginData();
					$data->setScheme($this->getDrmSchemeCoreValue());
					$data->setLicenseURL($this->constructUrl($playReadyProfile, self::PLUGIN_NAME, $customDataObject));
					$result->addToPluginData(self::PLUGIN_NAME, $data);
				}
			}
		}
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getDrmSchemeCoreValue()
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . PlayReadySchemeName::PLAYREADY;
		return kPluginableEnumsManager::apiToCore('DrmSchemeName', $value);
	}

	public function isSupportStreamerTypes($streamerType)
	{
		return in_array($streamerType ,array(PlaybackProtocol::SILVER_LIGHT));
	}

	public function constructUrl($playReadyProfile, $scheme, $customDataObject)
	{
		return $playReadyProfile->getLicenseServerUrl() . "/" . $scheme . "/license?custom_data=" . $customDataObject['custom_data'] . "&signature=" . $customDataObject['signature'];
	}

	/**
	 * @param entry $entry
	 * @return bool
	 */
	protected function shouldContribute(entry $entry)
	{
		if ($entry->getAccessControl())
		{
			foreach ($entry->getAccessControl()->getRulesArray() as $rule)
			{
				/**
				 * @var kRule $rule
				 */
				foreach ($rule->getActions() as $action)
				{
					/**
					 * @var kRuleAction $action
					 */
					if ($action->getType() == DrmAccessControlActionType::DRM_POLICY)
					{
						return true;
					}
				}
			}
		}
		return false;
	}
}


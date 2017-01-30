<?php
/**
 * @package plugins.fairplay
 */
class FairplayPlugin extends BorhanPlugin implements IBorhanEnumerator, IBorhanObjectLoader, IBorhanEntryContextDataContributor, IBorhanPending, IBorhanPlayManifestContributor, IBorhanPlaybackContextDataContributor
{
	const PLUGIN_NAME = 'fairplay';
	const SCHEME_NAME = 'fps';
	const SEARCH_DATA_SUFFIX = 's';

	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}

	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPlugin()
	 */
	public static function getScheme()
	{
		return self::SCHEME_NAME;
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if (is_null($baseEnumName))
			return array('FairplayProviderType');
		if ($baseEnumName == 'DrmProviderType')
			return array('FairplayProviderType');
		return array();
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if ($baseClass == 'BorhanDrmProfile' && $enumValue == FairplayPlugin::getFairplayProviderCoreValue() )
			return new BorhanFairplayDrmProfile();
		if ($baseClass == 'DrmProfile' && $enumValue ==  FairplayPlugin::getFairplayProviderCoreValue())
			return new FairplayDrmProfile();

		if (class_exists('Borhan_Client_Client'))
		{
			if ($baseClass == 'Borhan_Client_Drm_Type_DrmProfile' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::FAIRPLAY)
			{
				return new Borhan_Client_Fairplay_Type_FairplayDrmProfile();
			}
			if ($baseClass == 'Form_DrmProfileConfigureExtend_SubForm' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::FAIRPLAY)
			{
				return new Form_FairplayProfileConfigureExtend_SubForm();
			}
		}
		if ($baseClass == 'BorhanPluginData' && $enumValue == self::getPluginName())
			return new BorhanFairplayEntryContextPluginData();
		if ($baseClass == 'BorhanDrmPlaybackPluginData' && $enumValue == 'kFairPlayPlaybackPluginData')
			return new BorhanFairPlayPlaybackPluginData();
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if ($baseClass == 'BorhanDrmProfile' && $enumValue == FairplayPlugin::getFairplayProviderCoreValue() )
			return 'BorhanFairplayDrmProfile';
		if ($baseClass == 'DrmProfile' && $enumValue ==  FairplayPlugin::getFairplayProviderCoreValue())
			return 'FairplayDrmProfile';

		if (class_exists('Borhan_Client_Client'))
		{
			if ($baseClass == 'Borhan_Client_Drm_Type_DrmProfile' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::FAIRPLAY)
			{
				return 'Borhan_Client_Fairplay_Type_FairplayDrmProfile';
			}
			if ($baseClass == 'Form_DrmProfileConfigureExtend_SubForm' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::FAIRPLAY)
			{
				return 'Form_FairplayProfileConfigureExtend_SubForm';
			}
		}
		if ($baseClass == 'BorhanPluginData' && $enumValue == self::getPluginName())
			return 'BorhanFairplayEntryContextPluginData';
		return null;
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getFairplayProviderCoreValue()
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . FairplayProviderType::FAIRPLAY;
		return kPluginableEnumsManager::apiToCore('DrmProviderType', $value);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId) {
		return DrmPlugin::isAllowedPartner($partnerId);
	}

	public function contributeToEntryContextDataResult(entry $entry, accessControlScope $contextDataParams, kContextDataHelper $contextDataHelper)
	{
		if ($this->shouldContribute($entry))
		{
			$fairplayContextData = new kFairplayEntryContextPluginData();
			$fairplayProfile = DrmProfilePeer::retrieveByProviderAndPartnerID(FairplayPlugin::getFairplayProviderCoreValue(), kCurrentContext::getCurrentPartnerId());
			if (!is_null($fairplayProfile))
			{
				/**
				 * @var FairplayDrmProfile $fairplayProfile
				 */
				$fairplayContextData->publicCertificate = $fairplayProfile->getPublicCertificate();
				return $fairplayContextData;
			}
		}
		return null;
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

	/**
	 * Returns a Borhan dependency object that defines the relationship between two plugins.
	 *
	 * @return array<BorhanDependency> The Borhan dependency object
	 */
	public static function dependsOn()
	{
		$drmDependency = new BorhanDependency(DrmPlugin::getPluginName());

		return array($drmDependency);
	}

	public static function getManifestEditors($config)
	{
		$contributors = array();
		if (self::shouldEditManifest($config))
		{
			$contributor = new FairplayManifestEditor();
			$contributor->entryId = $config->entryId;
			$contributors[] = $contributor;
		}
		return $contributors;
	}

	private static function shouldEditManifest($config)
	{
		if($config->rendererClass == 'kM3U8ManifestRenderer' && $config->deliveryProfile->getType() == DeliveryProfileType::VOD_PACKAGER_HLS && $config->deliveryProfile->getAllowFairplayOffline())
			return true;

		return false;
	}

    public function contributeToPlaybackContextDataResult(entry $entry, kPlaybackContextDataParams $entryPlayingDataParams, kPlaybackContextDataResult $result, kContextDataHelper $contextDataHelper)
	{
		if ($this->shouldContribute($entry) && $this->isSupportStreamerTypes($entryPlayingDataParams->getDeliveryProfile()->getStreamerType()))
		{
			$fairplayProfile = DrmProfilePeer::retrieveByProviderAndPartnerID(FairplayPlugin::getFairplayProviderCoreValue(), kCurrentContext::getCurrentPartnerId());
			if ($fairplayProfile)
			{
				/* @var FairplayDrmProfile $fairplayProfile */

				$signingKey = kConf::get('signing_key', 'drm', null);
				if ($signingKey)
				{
					$customDataJson = DrmLicenseUtils::createCustomDataForEntry($entry->getId(), $entryPlayingDataParams->getFlavors(), $signingKey);
					$customDataObject = reset($customDataJson);
					$data = new kFairPlayPlaybackPluginData();
					$scheme = $this->getScheme();
					$data->setLicenseURL($this->constructUrl($fairplayProfile, $scheme, $customDataObject));
					$data->setScheme($scheme);
					$data->setCertificate($fairplayProfile->getPublicCertificate());
					$result->addToPluginData($scheme, $data);
				}
			}
		}
	}

	public function isSupportStreamerTypes($streamerType)
	{
		return in_array($streamerType ,array(PlaybackProtocol::APPLE_HTTP));
	}

	public function constructUrl($fairplayProfile, $scheme, $customDataObject)
	{
		return $fairplayProfile->getLicenseServerUrl() . "/" . $scheme . "/license?custom_data=" . $customDataObject['custom_data'] . "&signature=" . $customDataObject['signature'];
	}

}
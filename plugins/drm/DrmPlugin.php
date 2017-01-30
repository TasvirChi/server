<?php
/**
 * @package plugins.drm
 */
class DrmPlugin extends BorhanPlugin implements IBorhanServices, IBorhanAdminConsolePages, IBorhanPermissions, IBorhanEnumerator, IBorhanObjectLoader, IBorhanEntryContextDataContributor,IBorhanPermissionsEnabler, IBorhanPlaybackContextDataContributor
{
	const PLUGIN_NAME = 'drm';
	private static $schemes = array('cenc/widevine', 'cenc/playready');

    /* (non-PHPdoc)
     * @see IBorhanPlugin::getPluginName()
     */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}

	/* (non-PHPdoc)
   * @see IBorhanPlugin::getSchemes()
   */
	public static function getSchemes()
	{
		return self::$schemes;
	}

	/* (non-PHPdoc)
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap() {
		$map = array(
			'drmPolicy' => 'DrmPolicyService',
			'drmProfile' => 'DrmProfileService',
            'drmLicenseAccess' => 'DrmLicenseAccessService'
		);
		return $map;	
	}

	/* (non-PHPdoc)
	 * @see IBorhanAdminConsolePages::getApplicationPages()
	 */
	public static function getApplicationPages()
	{
		$pages = array();
		$pages[] = new DrmProfileListAction();
		$pages[] = new DrmProfileConfigureAction();
		$pages[] = new DrmProfileDeleteAction();

		return $pages;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId) {	
		
		if ($partnerId == Partner::ADMIN_CONSOLE_PARTNER_ID)
			return true;
		
		$partner = PartnerPeer::retrieveByPK($partnerId);
		if(!$partner)
			return false;
		return $partner->getPluginEnabled(self::PLUGIN_NAME);			
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{	
		if(is_null($baseEnumName))
			return array('DrmPermissionName', 'DrmConversionEngineType', 'DrmAccessControlActionType');
		if($baseEnumName == 'PermissionName')
			return array('DrmPermissionName');
        if($baseEnumName == 'conversionEngineType')
            return array('DrmConversionEngineType');
        if($baseEnumName == 'RuleActionType')
            return array('DrmAccessControlActionType');

		return array();
	}

	public static function getConfigParam($configName, $key)
	{
		$config = kConf::getMap($configName);
		if (!is_array($config))
		{
			BorhanLog::err($configName.' config section is not defined');
			return null;
		}

		if (!isset($config[$key]))
		{
			BorhanLog::err('The key '.$key.' was not found in the '.$configName.' config section');
			return null;
		}

		return $config[$key];
	}

    /* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
    public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
    {
        if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::CENC)
            return new KCEncOperationEngine($constructorArgs['params'], $constructorArgs['outFilePath']);
        if($baseClass == 'KDLOperatorBase' && $enumValue == self::getApiValue(DrmConversionEngineType::CENC))
            return new KDLOperatorDrm($enumValue);
        if ($baseClass == 'Borhan_Client_Drm_Type_DrmProfile' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::CENC)
            return new Borhan_Client_Drm_Type_DrmProfile();
        if($baseClass == 'kRuleAction' && $enumValue == DrmAccessControlActionType::DRM_POLICY)
            return new kAccessControlDrmPolicyAction();
        if($baseClass == 'BorhanRuleAction' && $enumValue == DrmAccessControlActionType::DRM_POLICY)
            return new BorhanAccessControlDrmPolicyAction();
	    if ($baseClass == 'BorhanPluginData' && $enumValue == self::getPluginName())
		    return new BorhanDrmEntryContextPluginData();
	    if ($baseClass == 'BorhanDrmPlaybackPluginData' && $enumValue == 'kDrmPlaybackPluginData')
		    return new BorhanDrmPlaybackPluginData();
        return null;
    }

    /* (non-PHPdoc)
    * @see IBorhanObjectLoader::getObjectClass()
     */
    public static function getObjectClass($baseClass, $enumValue)
    {
        if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::CENC)
            return "KDRMOperationEngine";
        if($baseClass == 'KDLOperatorBase' && $enumValue == self::getApiValue(DrmConversionEngineType::CENC))
            return "KDLOperatorrm";
        if($baseClass == 'BorhanDrmProfile' && $enumValue == BorhanDrmProviderType::CENC)
            return "BorhanDrmProfile";
        if($baseClass == 'DrmProfile' && $enumValue == BorhanDrmProviderType::CENC)
            return "DrmProfile";
        if ($baseClass == 'Borhan_Client_Drm_Type_DrmProfile' && $enumValue == Borhan_Client_Drm_Enum_DrmProviderType::CENC)
            return 'Borhan_Client_Drm_Type_DrmProfile';
        if($baseClass == 'kRuleAction' && $enumValue == DrmAccessControlActionType::DRM_POLICY)
            return 'kAccessControlDrmPolicyAction';
        if($baseClass == 'BorhanRuleAction' && $enumValue == DrmAccessControlActionType::DRM_POLICY)
            return 'BorhanAccessControlDrmPolicyAction';
	    if ($baseClass == 'BorhanPluginData' && $enumValue == self::getPluginName())
		    return 'BorhanDrmEntryContextPluginData';
        return null;
    }

    /**
     * @return string
     */
    protected static function getApiValue($value)
    {
        return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $value;
    }

    public function contributeToEntryContextDataResult(entry $entry, accessControlScope $contextDataParams, kContextDataHelper $contextDataHelper)
    {
	    if ($this->shouldContribute($entry ))
	    {
		    $signingKey = $this->getSigningKey();
		    if (!is_null($signingKey))
		    {
			    BorhanLog::info("Signing key is '$signingKey'");
			    $customDataJson = DrmLicenseUtils::createCustomData($entry->getId(), $contextDataHelper->getAllowedFlavorAssets(), $signingKey);
			    $drmContextData = new kDrmEntryContextPluginData();
			    $drmContextData->setFlavorData($customDataJson);
			    return $drmContextData;
		    }
	    }
	    return null;
    }

	public function contributeToPlaybackContextDataResult(entry $entry, kPlaybackContextDataParams $entryPlayingDataParams, kPlaybackContextDataResult $result, kContextDataHelper $contextDataHelper)
	{
		if ($this->shouldContribute($entry) && $this->isSupportStreamerTypes($entryPlayingDataParams->getDeliveryProfile()->getStreamerType()))
		{
			$dbProfile = DrmProfilePeer::retrieveByProviderAndPartnerID(BorhanDrmProviderType::CENC, kCurrentContext::getCurrentPartnerId());
			if ($dbProfile)
			{
				$signingKey = $dbProfile->getSigningKey();
				if ($signingKey)
				{
					$customDataJson = DrmLicenseUtils::createCustomDataForEntry($entry->getId(), $entryPlayingDataParams->getFlavors(), $signingKey);
					$customDataObject = reset($customDataJson);
					foreach ($this->getSchemes() as $scheme)
					{
						$data = new kDrmPlaybackPluginData();
						$data->setLicenseURL($this->constructUrl($dbProfile, $scheme, $customDataObject));
						$data->setScheme($scheme);
						$result->addToPluginData($scheme, $data);
					}
				}
			}
		}
	}

	public function isSupportStreamerTypes($streamerType)
	{
		return in_array($streamerType ,array(PlaybackProtocol::MPEG_DASH));
	}

	public function constructUrl($dbProfile, $scheme, $customDataObject)
	{
		return $dbProfile->getLicenseServerUrl() . "/" . $scheme . "/license?custom_data=" . $customDataObject['custom_data'] . "&signature=" . $customDataObject['signature'];
	}

    private function getSigningKey()
    {
	    $dbProfile = DrmProfilePeer::retrieveByProviderAndPartnerID(BorhanDrmProviderType::CENC, kCurrentContext::getCurrentPartnerId());
	    if (!is_null($dbProfile))
	    {
		    $signingKey = $dbProfile->getSigningKey();
		    return $signingKey;
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

	/* (non-PHPdoc)
	 * @see IBorhanPermissionsEnabler::permissionEnabled()
	 */
	public static function permissionEnabled($partnerId, $permissionName)
	{
		if ($permissionName == 'DRM_PLUGIN_PERMISSION')
		{
			kDrmPartnerSetup::setupPartner($partnerId);
		}
	}

}



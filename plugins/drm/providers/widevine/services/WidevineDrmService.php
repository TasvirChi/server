<?php

/**
 * WidevineDrmService serves as a license proxy to a Widevine license server
 * @service widevineDrm
 * @package plugins.widevine
 * @subpackage api.services
 */
class WidevineDrmService extends BorhanBaseService
{	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('asset');
		$this->applyPartnerFilterForClass('DrmProfile');
		
		if (!WidevinePlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
	}
		
	/**
	 * Get license for encrypted content playback
	 * 
	 * @action getLicense
	 * @param string $flavorAssetId
	 * @param string $referrer 64base encoded  
	 * @return string $response
	 * 
	 */
	public function getLicenseAction($flavorAssetId, $referrer = null)
	{
		BorhanResponseCacher::disableCache();
		
		BorhanLog::debug('get license for flavor asset: '.$flavorAssetId);
		try 
		{
			$requestParams = requestUtils::getRequestParams();
			if(!array_key_exists(WidevineLicenseProxyUtils::ASSETID, $requestParams))
			{
				BorhanLog::err('assetid is missing on the request');
				return WidevineLicenseProxyUtils::createErrorResponse(BorhanWidevineErrorCodes::WIDEVINE_ASSET_ID_CANNOT_BE_NULL, 0);
			}
			$wvAssetId = $requestParams[WidevineLicenseProxyUtils::ASSETID];
				
			$this->validateLicenseRequest($flavorAssetId, $wvAssetId, $referrer);
			$privileges = null;
			$isAdmin = false;
			if(kCurrentContext::$ks_object)
			{
				$privileges = kCurrentContext::$ks_object->getPrivileges();
				$isAdmin = kCurrentContext::$ks_object->isAdmin();
			}
			$response = WidevineLicenseProxyUtils::sendLicenseRequest($requestParams, $privileges, $isAdmin);
		}
		catch(BorhanWidevineLicenseProxyException $e)
		{
			BorhanLog::err($e);
			$response = WidevineLicenseProxyUtils::createErrorResponse($e->getWvErrorCode(), $wvAssetId);
		}
		catch (Exception $e)
		{
			BorhanLog::err($e);
			$response = WidevineLicenseProxyUtils::createErrorResponse(BorhanWidevineErrorCodes::GENERAL_ERROR, $wvAssetId);
		}	
		
		WidevineLicenseProxyUtils::printLicenseResponseStatus($response);
		return $response;
	}
	
	private function validateLicenseRequest($flavorAssetId, $wvAssetId, $referrer64base)
	{
		if(!$flavorAssetId)
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::FLAVOR_ASSET_ID_CANNOT_BE_NULL);
				
		$flavorAsset = $this->getFlavorAssetObject($flavorAssetId);

		if($flavorAsset->getType() != WidevinePlugin::getAssetTypeCoreValue(WidevineAssetType::WIDEVINE_FLAVOR))
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::WRONG_ASSET_TYPE);
			
		if($wvAssetId != $flavorAsset->getWidevineAssetId())
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::FLAVOR_ASSET_ID_DONT_MATCH_WIDEVINE_ASSET_ID);
					
		$entry = entryPeer::retrieveByPK($flavorAsset->getEntryId());
		if(!$entry)
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::FLAVOR_ASSET_ID_NOT_FOUND);
			
		$this->validateAccessControl($entry, $flavorAsset, $referrer64base);		
	}
	
	private function validateAccessControl(entry $entry, flavorAsset $flavorAsset, $referrer64base)
	{
		$referrer = base64_decode(str_replace(" ", "+", $referrer64base));
		if (!is_string($referrer))
			$referrer = ""; // base64_decode can return binary data		
		$secureEntryHelper = new KSecureEntryHelper($entry, kCurrentContext::$ks, $referrer, ContextType::PLAY);
		if(!$secureEntryHelper->isKsAdmin())
		{
			if(!$entry->isScheduledNow())
				throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::ENTRY_NOT_SCHEDULED_NOW);
			if($secureEntryHelper->isEntryInModeration())
				throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::ENTRY_MODERATION_ERROR);
		}
			
		if($secureEntryHelper->shouldBlock())
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::ACCESS_CONTROL_RESTRICTED);
			
		if(!$secureEntryHelper->isAssetAllowed($flavorAsset))
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::FLAVOR_ASSET_ID_NOT_FOUND);
	}
	
	private function getFlavorAssetObject($flavorAssetId)
	{
		try
		{
			if (!kCurrentContext::$ks)
			{
				$flavorAsset = kCurrentContext::initPartnerByAssetId($flavorAssetId);							
				// enforce entitlement
				$this->setPartnerFilters(kCurrentContext::getCurrentPartnerId());
				kEntitlementUtils::initEntitlementEnforcement();
			}
			else 
			{	
				$flavorAsset = assetPeer::retrieveById($flavorAssetId);
			}
			
			if (!$flavorAsset || $flavorAsset->getStatus() == asset::ASSET_STATUS_DELETED)
				throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::FLAVOR_ASSET_ID_NOT_FOUND);		

			return $flavorAsset;
		}
		catch (PropelException $e)
		{
			throw new BorhanWidevineLicenseProxyException(BorhanWidevineErrorCodes::FLAVOR_ASSET_ID_NOT_FOUND);
		}
	}
}

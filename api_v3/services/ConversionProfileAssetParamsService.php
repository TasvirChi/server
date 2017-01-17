<?php

/**
 * Manage the connection between Conversion Profiles and Asset Params
 *
 * @service conversionProfileAssetParams
 * @package api
 * @subpackage services
 */
class ConversionProfileAssetParamsService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		$this->applyPartnerFilterForClass('conversionProfile2');
		$this->applyPartnerFilterForClass('assetParams');
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::partnerGroup()
	 */
	protected function partnerGroup($peer = null) 	
	{
		if($this->actionName == 'list' && $peer == 'assetParams')
			return $this->partnerGroup . ',0';
		if($this->actionName == 'update' && $peer == 'assetParams')	
			return $this->partnerGroup . ',0';
			
		return $this->partnerGroup;
	}
	
	/**
	 * Lists asset parmas of conversion profile by ID
	 * 
	 * @action list
	 * @param BorhanConversionProfileAssetParamsFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanConversionProfileAssetParamsListResponse
	 */
	public function listAction(BorhanConversionProfileAssetParamsFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanConversionProfileAssetParamsFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
	
	/**
	 * Update asset parmas of conversion profile by ID
	 * 
	 * @action update
	 * @param int $conversionProfileId
	 * @param int $assetParamsId
	 * @param BorhanConversionProfileAssetParams $conversionProfileAssetParams
	 * @return BorhanConversionProfileAssetParams
	 */
	public function updateAction($conversionProfileId, $assetParamsId, BorhanConversionProfileAssetParams $conversionProfileAssetParams)
	{
		$conversionProfile = conversionProfile2Peer::retrieveByPK($conversionProfileId);
		if(!$conversionProfile)
			throw new BorhanAPIException(BorhanErrors::CONVERSION_PROFILE_ID_NOT_FOUND, $conversionProfileId);
			
		$flavorParamsConversionProfile = flavorParamsConversionProfilePeer::retrieveByFlavorParamsAndConversionProfile($assetParamsId, $conversionProfileId);
		if(!$flavorParamsConversionProfile)
			throw new BorhanAPIException(BorhanErrors::CONVERSION_PROFILE_ASSET_PARAMS_NOT_FOUND, $conversionProfileId, $assetParamsId);
			
		$conversionProfileAssetParams->toUpdatableObject($flavorParamsConversionProfile);
		$flavorParamsConversionProfile->save();
			
		$conversionProfileAssetParams->fromObject($flavorParamsConversionProfile, $this->getResponseProfile());
		return $conversionProfileAssetParams;
	}
}
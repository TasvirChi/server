<?php

/**
 * Add & Manage Thumb Params
 *
 * @service thumbParams
 * @package api
 * @subpackage services
 */
class ThumbParamsService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		$this->applyPartnerFilterForClass('conversionProfile2');
		$this->applyPartnerFilterForClass('asset');
		$this->applyPartnerFilterForClass('assetParamsOutput');
		$this->applyPartnerFilterForClass('assetParams');
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::partnerGroup()
	 */
	protected function partnerGroup($peer = null)
	{
		if(
			$this->actionName == 'get' ||
			$this->actionName == 'list'
			)
			return $this->partnerGroup . ',0';
			
		return $this->partnerGroup;
	}
	
	protected function globalPartnerAllowed($actionName)
	{
		if ($actionName === 'get') {
			return true;
		}
		if ($actionName === 'list') {
			return true;
		}
		return parent::globalPartnerAllowed($actionName);
	}
	
	/**
	 * Add new Thumb Params
	 * 
	 * @action add
	 * @param BorhanThumbParams $thumbParams
	 * @return BorhanThumbParams
	 */
	public function addAction(BorhanThumbParams $thumbParams)
	{	
		$thumbParamsDb = new thumbParams();
		$thumbParams->toInsertableObject($thumbParamsDb);
		
		$thumbParamsDb->setPartnerId($this->getPartnerId());
		$thumbParamsDb->save();
		
		$thumbParams->fromObject($thumbParamsDb, $this->getResponseProfile());
		return $thumbParams;
	}
	
	/**
	 * Get Thumb Params by ID
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanThumbParams
	 */
	public function getAction($id)
	{
		$thumbParamsDb = assetParamsPeer::retrieveByPK($id);
		
		if (!$thumbParamsDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_ID_NOT_FOUND, $id);
			
		$thumbParams = BorhanFlavorParamsFactory::getFlavorParamsInstance($thumbParamsDb->getType());
		$thumbParams->fromObject($thumbParamsDb, $this->getResponseProfile());
		
		return $thumbParams;
	}
	
	/**
	 * Update Thumb Params by ID
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanThumbParams $thumbParams
	 * @return BorhanThumbParams
	 */
	public function updateAction($id, BorhanThumbParams $thumbParams)
	{
		$thumbParamsDb = assetParamsPeer::retrieveByPK($id);
		if (!$thumbParamsDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_ID_NOT_FOUND, $id);
			
		$thumbParams->toUpdatableObject($thumbParamsDb);
		$thumbParamsDb->save();
			
		$thumbParams->fromObject($thumbParamsDb, $this->getResponseProfile());
		return $thumbParams;
	}
	
	/**
	 * Delete Thumb Params by ID
	 * 
	 * @action delete
	 * @param int $id
	 */
	public function deleteAction($id)
	{
		$thumbParamsDb = assetParamsPeer::retrieveByPK($id);
		if (!$thumbParamsDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_ID_NOT_FOUND, $id);
			
		$thumbParamsDb->setDeletedAt(time());
		$thumbParamsDb->save();
	}
	
	/**
	 * List Thumb Params by filter with paging support (By default - all system default params will be listed too)
	 * 
	 * @action list
	 * @param BorhanThumbParamsFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanThumbParamsListResponse
	 */
	public function listAction(BorhanThumbParamsFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanThumbParamsFilter();
			
		if(!$pager)
		{
			$pager = new BorhanFilterPager();
		}

		$types = BorhanPluginManager::getExtendedTypes(assetParamsPeer::OM_CLASS, assetType::THUMBNAIL);
		return $filter->getTypeListResponse($pager, $this->getResponseProfile(), $types);
	}
	
	/**
	 * Get Thumb Params by Conversion Profile ID
	 * 
	 * @action getByConversionProfileId
	 * @param int $conversionProfileId
	 * @return BorhanThumbParamsArray
	 */
	public function getByConversionProfileIdAction($conversionProfileId)
	{
		$conversionProfileDb = conversionProfile2Peer::retrieveByPK($conversionProfileId);
		if (!$conversionProfileDb)
			throw new BorhanAPIException(BorhanErrors::CONVERSION_PROFILE_ID_NOT_FOUND, $conversionProfileId);
			
		$thumbParamsConversionProfilesDb = $conversionProfileDb->getflavorParamsConversionProfilesJoinflavorParams();
		$thumbParamsDb = array();
		foreach($thumbParamsConversionProfilesDb as $item)
		{
			/* @var $item flavorParamsConversionProfile */
			$thumbParamsDb[] = $item->getassetParams();
		}
		
		$thumbParams = BorhanThumbParamsArray::fromDbArray($thumbParamsDb, $this->getResponseProfile());
		
		return $thumbParams; 
	}
}
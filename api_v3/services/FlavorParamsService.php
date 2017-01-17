<?php

/**
 * Add & Manage Flavor Params
 *
 * @service flavorParams
 * @package api
 * @subpackage services
 */
class FlavorParamsService extends BorhanBaseService
{
	
	const PROPERTY_MIN_LENGTH = 1; 
	
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
		if( $this->actionName == 'get') {
			assetParamsPeer::setIsDefaultInDefaultCriteria(false);
			return $this->partnerGroup . ',0';
		} else if ($this->actionName == 'list') {
			return $this->partnerGroup . ',0';
		}
			
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
	 * Add new Flavor Params
	 * 
	 * @action add
	 * @param BorhanFlavorParams $flavorParams
	 * @return BorhanFlavorParams
	 */
	public function addAction(BorhanFlavorParams $flavorParams)
	{
		$flavorParams->validatePropertyMinLength("name", self::PROPERTY_MIN_LENGTH);
		
		$flavorParamsDb = $flavorParams->toObject();
		
		$flavorParamsDb->setPartnerId($this->getPartnerId());
		$flavorParamsDb->save();
		
		$flavorParams = BorhanFlavorParamsFactory::getFlavorParamsInstance($flavorParamsDb->getType());
		$flavorParams->fromObject($flavorParamsDb, $this->getResponseProfile());
		return $flavorParams;
	}
	
	/**
	 * Get Flavor Params by ID
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanFlavorParams
	 */
	public function getAction($id)
	{
		$flavorParamsDb = assetParamsPeer::retrieveByPK($id);
		
		if (!$flavorParamsDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_ID_NOT_FOUND, $id);
			
		$flavorParams = BorhanFlavorParamsFactory::getFlavorParamsInstance($flavorParamsDb->getType());
		$flavorParams->fromObject($flavorParamsDb, $this->getResponseProfile());
		
		return $flavorParams;
	}
	
	/**
	 * Update Flavor Params by ID
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanFlavorParams $flavorParams
	 * @return BorhanFlavorParams
	 */
	public function updateAction($id, BorhanFlavorParams $flavorParams)
	{
		if ($flavorParams->name !== null)
			$flavorParams->validatePropertyMinLength("name", self::PROPERTY_MIN_LENGTH);
			
		$flavorParamsDb = assetParamsPeer::retrieveByPK($id);
		if (!$flavorParamsDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_ID_NOT_FOUND, $id);
			
		$flavorParams->toUpdatableObject($flavorParamsDb);
		$flavorParamsDb->save();
			
		$flavorParams = BorhanFlavorParamsFactory::getFlavorParamsInstance($flavorParamsDb->getType());
		$flavorParams->fromObject($flavorParamsDb, $this->getResponseProfile());
		return $flavorParams;
	}
	
	/**
	 * Delete Flavor Params by ID
	 * 
	 * @action delete
	 * @param int $id
	 */
	public function deleteAction($id)
	{
		$flavorParamsDb = assetParamsPeer::retrieveByPK($id);
		if (!$flavorParamsDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_ID_NOT_FOUND, $id);
			
		$flavorParamsDb->setDeletedAt(time());
		$flavorParamsDb->save();
	}
	
	/**
	 * List Flavor Params by filter with paging support (By default - all system default params will be listed too)
	 * 
	 * @action list
	 * @param BorhanFlavorParamsFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanFlavorParamsListResponse
	 */
	public function listAction(BorhanFlavorParamsFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanFlavorParamsFilter();
			
		if(!$pager)
		{
			$pager = new BorhanFilterPager();
		}
			
		$types = assetParamsPeer::retrieveAllFlavorParamsTypes();			
		return $filter->getTypeListResponse($pager, $this->getResponseProfile(), $types);
	}
	
	/**
	 * Get Flavor Params by Conversion Profile ID
	 * 
	 * @action getByConversionProfileId
	 * @param int $conversionProfileId
	 * @return BorhanFlavorParamsArray
	 */
	public function getByConversionProfileIdAction($conversionProfileId)
	{
		$conversionProfileDb = conversionProfile2Peer::retrieveByPK($conversionProfileId);
		if (!$conversionProfileDb)
			throw new BorhanAPIException(BorhanErrors::CONVERSION_PROFILE_ID_NOT_FOUND, $conversionProfileId);
			
		$flavorParamsConversionProfilesDb = $conversionProfileDb->getflavorParamsConversionProfilesJoinflavorParams();
		$flavorParamsDb = array();
		foreach($flavorParamsConversionProfilesDb as $item)
		{
			/* @var $item flavorParamsConversionProfile */
			$flavorParamsDb[] = $item->getassetParams();
		}
		
		$flavorParams = BorhanFlavorParamsArray::fromDbArray($flavorParamsDb, $this->getResponseProfile());
		
		return $flavorParams; 
	}
}
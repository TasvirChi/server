<?php
/**
 * Flavor Params Output service
 *
 * @service flavorParamsOutput
 * @package api
 * @subpackage services
 */
class FlavorParamsOutputService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		if($this->getPartnerId() != Partner::BATCH_PARTNER_ID && $this->getPartnerId() != Partner::ADMIN_CONSOLE_PARTNER_ID)
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
	}
	
	/**
	 * Get flavor params output object by ID
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanFlavorParamsOutput
	 * @throws BorhanErrors::FLAVOR_PARAMS_OUTPUT_ID_NOT_FOUND
	 */
	public function getAction($id)
	{
		$flavorParamsOutputDb = assetParamsOutputPeer::retrieveByPK($id);
		
		if (!$flavorParamsOutputDb)
			throw new BorhanAPIException(BorhanErrors::FLAVOR_PARAMS_OUTPUT_ID_NOT_FOUND, $id);
			
		$flavorParamsOutput = BorhanFlavorParamsFactory::getFlavorParamsOutputInstance($flavorParamsOutputDb->getType());
		$flavorParamsOutput->fromObject($flavorParamsOutputDb, $this->getResponseProfile());
		
		return $flavorParamsOutput;
	}
	
	/**
	 * List flavor params output objects by filter and pager
	 * 
	 * @action list
	 * @param BorhanFlavorParamsOutputFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanFlavorParamsOutputListResponse
	 */
	function listAction(BorhanFlavorParamsOutputFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanFlavorParamsOutputFilter();
			
		if(!$pager)
		{
			$pager = new BorhanFilterPager();
		}
			
		$types = BorhanPluginManager::getExtendedTypes(assetParamsOutputPeer::OM_CLASS, assetType::FLAVOR);
		return $filter->getTypeListResponse($pager, $this->getResponseProfile(), $types);
	}
}

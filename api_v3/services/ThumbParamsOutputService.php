<?php
/**
 * Thumbnail Params Output service
 *
 * @service thumbParamsOutput
 * @package api
 * @subpackage services
 */
class ThumbParamsOutputService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		if($this->getPartnerId() != Partner::BATCH_PARTNER_ID && $this->getPartnerId() != Partner::ADMIN_CONSOLE_PARTNER_ID)
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
	}
	
	/**
	 * Get thumb params output object by ID
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanThumbParamsOutput
	 * @throws BorhanErrors::THUMB_PARAMS_OUTPUT_ID_NOT_FOUND
	 */
	public function getAction($id)
	{
		$thumbParamsOutputDb = assetParamsOutputPeer::retrieveByPK($id);
		
		if (!$thumbParamsOutputDb)
			throw new BorhanAPIException(BorhanErrors::THUMB_PARAMS_OUTPUT_ID_NOT_FOUND, $id);
			
		$thumbParamsOutput = new BorhanThumbParamsOutput();
		$thumbParamsOutput->fromObject($thumbParamsOutputDb, $this->getResponseProfile());
		
		return $thumbParamsOutput;
	}
	
	/**
	 * List thumb params output objects by filter and pager
	 * 
	 * @action list
	 * @param BorhanThumbParamsOutputFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanThumbParamsOutputListResponse
	 */
	function listAction(BorhanThumbParamsOutputFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanThumbParamsOutputFilter();
			
		if(!$pager)
		{
			$pager = new BorhanFilterPager();
		}
			
		$types = BorhanPluginManager::getExtendedTypes(assetParamsOutputPeer::OM_CLASS, assetType::THUMBNAIL);
		return $filter->getTypeListResponse($pager, $this->getResponseProfile(), $types);
	}
}

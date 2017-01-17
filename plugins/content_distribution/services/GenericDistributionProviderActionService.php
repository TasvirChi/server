<?php
/**
 * Generic Distribution Provider Actions service
 *
 * @service genericDistributionProviderAction
 * @package plugins.contentDistribution
 * @subpackage api.services
 */
class GenericDistributionProviderActionService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('GenericDistributionProviderAction');
		
		if(!ContentDistributionPlugin::isAllowedPartner(kCurrentContext::$master_partner_id))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, ContentDistributionPlugin::PLUGIN_NAME);
	}
	
	/**
	 * Add new Generic Distribution Provider Action
	 * 
	 * @action add
	 * @param BorhanGenericDistributionProviderAction $genericDistributionProviderAction
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_NOT_FOUND
	 */
	function addAction(BorhanGenericDistributionProviderAction $genericDistributionProviderAction)
	{
		$genericDistributionProviderAction->validatePropertyNotNull("genericDistributionProviderId");
		
		$dbGenericDistributionProvider = GenericDistributionProviderPeer::retrieveByPK($genericDistributionProviderAction->genericDistributionProviderId);
		if (!$dbGenericDistributionProvider)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_NOT_FOUND, $genericDistributionProviderAction->genericDistributionProviderId);
			
		$dbGenericDistributionProviderAction = new GenericDistributionProviderAction();
		$genericDistributionProviderAction->toInsertableObject($dbGenericDistributionProviderAction);
		$dbGenericDistributionProviderAction->setPartnerId($dbGenericDistributionProvider->getPartnerId());			
		$dbGenericDistributionProviderAction->setStatus(GenericDistributionProviderStatus::ACTIVE);
		$dbGenericDistributionProviderAction->save();
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}

	
	/**
	 * Add MRSS transform file to generic distribution provider action
	 * 
	 * @action addMrssTransform
	 * @param int $id the id of the generic distribution provider action
	 * @param string $xslData XSL MRSS transformation data
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function addMrssTransformAction($id, $xslData)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$dbGenericDistributionProviderAction->incrementMrssTransformerVersion();
		$dbGenericDistributionProviderAction->save();
		
		$key = $dbGenericDistributionProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_MRSS_TRANSFORMER);
		kFileSyncUtils::file_put_contents($key, $xslData);
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}

	
	/**
	 * Add MRSS transform file to generic distribution provider action
	 * 
	 * @action addMrssTransformFromFile
	 * @param int $id the id of the generic distribution provider action
	 * @param file $xslFile XSL MRSS transformation file
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND
	 */
	function addMrssTransformFromFileAction($id, $xslFile)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$filePath = $xslFile['tmp_name'];
		if(!file_exists($filePath))
			throw new BorhanAPIException(BorhanErrors::UPLOADED_FILE_NOT_FOUND, $xslFile['name']);
			
		$dbGenericDistributionProviderAction->incrementMrssTransformerVersion();
		$dbGenericDistributionProviderAction->save();
		
		$key = $dbGenericDistributionProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_MRSS_TRANSFORMER);
		kFileSyncUtils::moveFromFile($filePath, $key);
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}

	
	/**
	 * Add MRSS validate file to generic distribution provider action
	 * 
	 * @action addMrssValidate
	 * @param int $id the id of the generic distribution provider action
	 * @param string $xsdData XSD MRSS validatation data
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function addMrssValidateAction($id, $xsdData)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$dbGenericDistributionProviderAction->incrementMrssValidatorVersion();
		$dbGenericDistributionProviderAction->save();
		
		$key = $dbGenericDistributionProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_MRSS_VALIDATOR);
		kFileSyncUtils::file_put_contents($key, $xsdData);
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}

	
	/**
	 * Add MRSS validate file to generic distribution provider action
	 * 
	 * @action addMrssValidateFromFile
	 * @param int $id the id of the generic distribution provider action
	 * @param file $xsdFile XSD MRSS validatation file
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND
	 */
	function addMrssValidateFromFileAction($id, $xsdFile)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$filePath = $xsdFile['tmp_name'];
		if(!file_exists($filePath))
			throw new BorhanAPIException(BorhanErrors::UPLOADED_FILE_NOT_FOUND, $xsdFile['name']);
			
		$dbGenericDistributionProviderAction->incrementMrssValidatorVersion();
		$dbGenericDistributionProviderAction->save();
		
		$key = $dbGenericDistributionProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_MRSS_VALIDATOR);
		kFileSyncUtils::moveFromFile($filePath, $key);
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}

	
	/**
	 * Add results transform file to generic distribution provider action
	 * 
	 * @action addResultsTransform
	 * @param int $id the id of the generic distribution provider action
	 * @param string $transformData transformation data xsl, xPath or regex
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function addResultsTransformAction($id, $transformData)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$dbGenericDistributionProviderAction->incrementResultsTransformerVersion();
		$dbGenericDistributionProviderAction->save();
		
		$key = $dbGenericDistributionProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_RESULTS_TRANSFORMER);
		kFileSyncUtils::file_put_contents($key, $transformData);
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}

	
	/**
	 * Add MRSS transform file to generic distribution provider action
	 * 
	 * @action addResultsTransformFromFile
	 * @param int $id the id of the generic distribution provider action
	 * @param file $transformFile transformation file xsl, xPath or regex
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND
	 */
	function addResultsTransformFromFileAction($id, $transformFile)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$filePath = $transformFile['tmp_name'];
		if(!file_exists($filePath))
			throw new BorhanAPIException(BorhanErrors::UPLOADED_FILE_NOT_FOUND, $transformFile['name']);
			
		$dbGenericDistributionProviderAction->incrementResultsTransformerVersion();
		$dbGenericDistributionProviderAction->save();
		
		$key = $dbGenericDistributionProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_RESULTS_TRANSFORMER);
		kFileSyncUtils::moveFromFile($filePath, $key);
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}
	
	
	/**
	 * Get Generic Distribution Provider Action by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
			
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}
	
	
	/**
	 * Get Generic Distribution Provider Action by provider id
	 * 
	 * @action getByProviderId
	 * @param int $genericDistributionProviderId
	 * @param BorhanDistributionAction $actionType
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function getByProviderIdAction($genericDistributionProviderId, $actionType)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByProviderAndAction($genericDistributionProviderId, $actionType);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $genericDistributionProviderId);
	
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}
	
	/**
	 * Update Generic Distribution Provider Action by provider id
	 * 
	 * @action updateByProviderId
	 * @param int $genericDistributionProviderId
	 * @param BorhanDistributionAction $actionType
	 * @param BorhanGenericDistributionProviderAction $genericDistributionProviderAction
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function updateByProviderIdAction($genericDistributionProviderId, $actionType, BorhanGenericDistributionProviderAction $genericDistributionProviderAction)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByProviderAndAction($genericDistributionProviderId, $actionType);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $genericDistributionProviderId);
	
		$genericDistributionProviderAction->toUpdatableObject($dbGenericDistributionProviderAction);
		$dbGenericDistributionProviderAction->save();
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}
	
	/**
	 * Update Generic Distribution Provider Action by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanGenericDistributionProviderAction $genericDistributionProviderAction
	 * @return BorhanGenericDistributionProviderAction
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function updateAction($id, BorhanGenericDistributionProviderAction $genericDistributionProviderAction)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);
		
		$genericDistributionProviderAction->toUpdatableObject($dbGenericDistributionProviderAction);
		$dbGenericDistributionProviderAction->save();
		
		$genericDistributionProviderAction = new BorhanGenericDistributionProviderAction();
		$genericDistributionProviderAction->fromObject($dbGenericDistributionProviderAction, $this->getResponseProfile());
		return $genericDistributionProviderAction;
	}
	
	/**
	 * Delete Generic Distribution Provider Action by id
	 * 
	 * @action delete
	 * @param int $id
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function deleteAction($id)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByPK($id);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $id);

		$dbGenericDistributionProviderAction->setStatus(GenericDistributionProviderStatus::DELETED);
		$dbGenericDistributionProviderAction->save();
	}
	
	/**
	 * Delete Generic Distribution Provider Action by provider id
	 * 
	 * @action deleteByProviderId
	 * @param int $genericDistributionProviderId
	 * @param BorhanDistributionAction $actionType
	 * @throws ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND
	 */
	function deleteByProviderIdAction($genericDistributionProviderId, $actionType)
	{
		$dbGenericDistributionProviderAction = GenericDistributionProviderActionPeer::retrieveByProviderAndAction($genericDistributionProviderId, $actionType);
		if (!$dbGenericDistributionProviderAction)
			throw new BorhanAPIException(ContentDistributionErrors::GENERIC_DISTRIBUTION_PROVIDER_ACTION_NOT_FOUND, $genericDistributionProviderId);

		$dbGenericDistributionProviderAction->setStatus(GenericDistributionProviderStatus::DELETED);
		$dbGenericDistributionProviderAction->save();
	}
	
	
	/**
	 * List all distribution providers
	 * 
	 * @action list
	 * @param BorhanGenericDistributionProviderActionFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanGenericDistributionProviderActionListResponse
	 */
	function listAction(BorhanGenericDistributionProviderActionFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanGenericDistributionProviderActionFilter();
			
		$c = new Criteria();
		$genericDistributionProviderActionFilter = new GenericDistributionProviderActionFilter();
		$filter->toObject($genericDistributionProviderActionFilter);
		
		$genericDistributionProviderActionFilter->attachToCriteria($c);
		$count = GenericDistributionProviderActionPeer::doCount($c);
		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria($c);
		$list = GenericDistributionProviderActionPeer::doSelect($c);
		
		$response = new BorhanGenericDistributionProviderActionListResponse();
		$response->objects = BorhanGenericDistributionProviderActionArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
	
		return $response;
	}	
}

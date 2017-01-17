<?php
/**
 * Business-process case service lets you get information about processes
 * @service businessProcessCase
 * @package plugins.businessProcessNotification
 * @subpackage api.services
 */
class BusinessProcessCaseService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		$partnerId = $this->getPartnerId();
		if (!EventNotificationPlugin::isAllowedPartner($partnerId))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, EventNotificationPlugin::PLUGIN_NAME);
			
		$this->applyPartnerFilterForClass('EventNotificationTemplate');
	}
	
	/**
	 * Abort business-process case
	 * 
	 * @action abort
	 * @param BorhanEventNotificationEventObjectType $objectType
	 * @param string $objectId
	 * @param int $businessProcessStartNotificationTemplateId
	 *
	 * @throws BorhanEventNotificationErrors::EVENT_NOTIFICATION_TEMPLATE_NOT_FOUND
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_CASE_NOT_FOUND
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */		
	public function abortAction($objectType, $objectId, $businessProcessStartNotificationTemplateId)
	{
		$dbObject = kEventNotificationFlowManager::getObject($objectType, $objectId);
		if(!$dbObject)
		{
			throw new BorhanAPIException(BorhanErrors::OBJECT_NOT_FOUND);
		}
		
		$dbTemplate = EventNotificationTemplatePeer::retrieveByPK($businessProcessStartNotificationTemplateId);
		if(!$dbTemplate || !($dbTemplate instanceof BusinessProcessStartNotificationTemplate))
		{
			throw new BorhanAPIException(BorhanEventNotificationErrors::EVENT_NOTIFICATION_TEMPLATE_NOT_FOUND, $businessProcessStartNotificationTemplateId);
		}
		
		$caseIds = $dbTemplate->getCaseIds($dbObject, false);
		if(!count($caseIds))
		{
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_CASE_NOT_FOUND);
		}
		
		$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($dbTemplate->getServerId());
		if (!$dbBusinessProcessServer)
		{
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND, $dbTemplate->getServerId());
		}
		
		$server = new BorhanActivitiBusinessProcessServer();
		$server->fromObject($dbBusinessProcessServer);
		$provider = kBusinessProcessProvider::get($server);
		
		foreach($caseIds as $caseId)
		{
			$provider->abortCase($caseId);
		}
	}

	/**
	 * Server business-process case diagram
	 * 
	 * @action serveDiagram
	 * @param BorhanEventNotificationEventObjectType $objectType
	 * @param string $objectId
	 * @param int $businessProcessStartNotificationTemplateId
	 * @return file
	 *
	 * @throws BorhanEventNotificationErrors::EVENT_NOTIFICATION_TEMPLATE_NOT_FOUND
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_CASE_NOT_FOUND
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */		
	public function serveDiagramAction($objectType, $objectId, $businessProcessStartNotificationTemplateId)
	{
		$dbObject = kEventNotificationFlowManager::getObject($objectType, $objectId);
		if(!$dbObject)
		{
			throw new BorhanAPIException(BorhanErrors::OBJECT_NOT_FOUND);
		}
		
		$dbTemplate = EventNotificationTemplatePeer::retrieveByPK($businessProcessStartNotificationTemplateId);
		if(!$dbTemplate || !($dbTemplate instanceof BusinessProcessStartNotificationTemplate))
		{
			throw new BorhanAPIException(BorhanEventNotificationErrors::EVENT_NOTIFICATION_TEMPLATE_NOT_FOUND, $businessProcessStartNotificationTemplateId);
		}
		
		$caseIds = $dbTemplate->getCaseIds($dbObject, false);
		if(!count($caseIds))
		{
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_CASE_NOT_FOUND);
		}
		
		$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($dbTemplate->getServerId());
		if (!$dbBusinessProcessServer)
		{
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND, $dbTemplate->getServerId());
		}
		
		$businessProcessServer = BorhanBusinessProcessServer::getInstanceByType($dbBusinessProcessServer->getType());
		$businessProcessServer->fromObject($dbBusinessProcessServer);
		$provider = kBusinessProcessProvider::get($businessProcessServer);
		
		$caseId = end($caseIds);
		
		$filename = myContentStorage::getFSCacheRootPath() . 'bpm_diagram/bpm_';
		$filename .= $objectId . '_';
		$filename .= $businessProcessStartNotificationTemplateId . '_';
		$filename .= $caseId . '.jpg';
		
		$url = $provider->getCaseDiagram($caseId, $filename);
		
		KCurlWrapper::getDataFromFile($url, $filename);
		$mimeType = kFile::mimeType($filename);			
		return $this->dumpFile($filename, $mimeType);
	}
	
	/**
	 * list business-process cases
	 * 
	 * @action list
	 * @param BorhanEventNotificationEventObjectType $objectType
	 * @param string $objectId
	 * @return BorhanBusinessProcessCaseArray
	 * 
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_CASE_NOT_FOUND
	 * @throws BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_SERVER_NOT_FOUND
	 */
	public function listAction($objectType, $objectId)
	{
		$dbObject = kEventNotificationFlowManager::getObject($objectType, $objectId);
		if(!$dbObject)
		{
			throw new BorhanAPIException(BorhanErrors::OBJECT_NOT_FOUND);
		}
		
		$cases = BusinessProcessCasePeer::retrieveCasesByObjectIdObjecType($objectId, $objectType);
		if(!count($cases))
		{
			throw new BorhanAPIException(BorhanBusinessProcessNotificationErrors::BUSINESS_PROCESS_CASE_NOT_FOUND);
		}
		
		$array = new BorhanBusinessProcessCaseArray();
		foreach($cases as $case)
		{
			/* @var $case BusinessProcessCase */
			$dbBusinessProcessServer = BusinessProcessServerPeer::retrieveByPK($case->getServerId());
			if (!$dbBusinessProcessServer)
			{
				BorhanLog::info("Business-Process server [" . $dbTemplate->getServerId() . "] not found");
				continue;
			}
			
			$businessProcessServer = BorhanBusinessProcessServer::getInstanceByType($dbBusinessProcessServer->getType());
			$businessProcessServer->fromObject($dbBusinessProcessServer);
			$provider = kBusinessProcessProvider::get($businessProcessServer);
			if(!$provider)
			{
				BorhanLog::info("Provider [" . $businessProcessServer->type . "] not found");
				continue;
			}

			$latestCaseId = $case->getCaseId();
			if($latestCaseId)
			{
				try {
					$case = $provider->getCase($latestCaseId);
					$businessProcessCase = new BorhanBusinessProcessCase();
					$businessProcessCase->businessProcessStartNotificationTemplateId = $templateId;
					$businessProcessCase->fromObject($case);
					$array[] = $businessProcessCase;
				} catch (ActivitiClientException $e) {
					BorhanLog::err("Case [$latestCaseId] not found: " . $e->getMessage());
				}
			}
		}
		
		return $array;
	}
}

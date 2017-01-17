<?php
/**
 * Integration service lets you dispatch integration tasks
 * @service integration
 * @package plugins.integration
 * @subpackage api.services
 */
class IntegrationService extends BorhanBaseService
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
	 * Dispatch integration task
	 * 
	 * @action dispatch
	 * @param BorhanIntegrationJobData $data
	 * @param BorhanBatchJobObjectType $objectType
	 * @param string $objectId
	 * @throws BorhanIntegrationErrors::INTEGRATION_DISPATCH_FAILED
	 * @return int
	 */		
	public function dispatchAction(BorhanIntegrationJobData $data, $objectType, $objectId)
	{
		$jobData = $data->toObject();
		$coreObjectType = kPluginableEnumsManager::apiToCore('BatchJobObjectType', $objectType);
		$job = kIntegrationFlowManager::addintegrationJob($coreObjectType, $objectId, $jobData);
		if(!$job)
			throw new BorhanAPIException(BorhanIntegrationErrors::INTEGRATION_DISPATCH_FAILED, $objectType);
			
		return $job->getId();
	}

	/**
	 * @action notify
	 * @disableTags TAG_WIDGET_SESSION,TAG_ENTITLEMENT_ENTRY,TAG_ENTITLEMENT_CATEGORY
	 * @param int $id integration job id
	 */
	public function notifyAction($id) 
	{
		$coreType = IntegrationPlugin::getBatchJobTypeCoreValue(IntegrationBatchJobType::INTEGRATION);
		$batchJob = BatchJobPeer::retrieveByPK($id);
		$invalidJobId = false;
		$invalidKs = false;
		
		if(!self::validateKs($batchJob))
		{
			$invalidKs = true;
			BorhanLog::err("ks not valid for notifying job [$id]");
		}
		elseif(!$batchJob)
		{
			$invalidJobId = true;
			BorhanLog::err("Job [$id] not found");
		}
		elseif($batchJob->getJobType() != $coreType)
		{
			$invalidJobId = true;
			BorhanLog::err("Job [$id] wrong type [" . $batchJob->getJobType() . "] expected [" . $coreType . "]");
		}
		elseif($batchJob->getStatus() != BorhanBatchJobStatus::ALMOST_DONE)
		{
			$invalidJobId = true;
			BorhanLog::err("Job [$id] wrong status [" . $batchJob->getStatus() . "] expected [" . BorhanBatchJobStatus::ALMOST_DONE . "]");
		}
		elseif($batchJob->getPartnerId() != kCurrentContext::getCurrentPartnerId())
		{
			$invalidKs = true;
			BorhanLog::err("Job [$id] of wrong partner [" . $batchJob->getPartnerId() . "] expected [" . kCurrentContext::getCurrentPartnerId() . "]");
		}

		if($invalidJobId)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_BATCHJOB_ID, $id);
		}
		if($invalidKs)
		{
			throw new BorhanAPIException(BorhanIntegrationErrors::INTEGRATION_NOTIFY_FAILED);
		}
			
		kJobsManager::updateBatchJob($batchJob, BorhanBatchJobStatus::FINISHED);
	}

	public static function validateKs($job)
	{	
		$dcParams = kDataCenterMgr::getCurrentDc();
		$token = $dcParams["secret"];
		
		$createdString = md5($job->getId() . $token);
		
		$ks = kCurrentContext::$ks_object;
		if($createdString == $ks->additional_data)
			return true;
		
		return false;
	}
}

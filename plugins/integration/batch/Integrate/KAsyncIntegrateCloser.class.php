<?php
/**
 * @package plugins.integration
 * @subpackage Scheduler
 */
class KAsyncIntegrateCloser extends KJobCloserWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::INTEGRATION;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->close($job, $job->data);
	}
	
	protected function close(BorhanBatchJob $job, BorhanIntegrationJobData $data)
	{
		if(($job->queueTime + self::$taskConfig->params->maxTimeBeforeFail) < time())
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::CLOSER_TIMEOUT, 'Timed out', BorhanBatchJobStatus::FAILED);
		}
		
		$engine = $this->getEngine($job->jobSubType);
		if(!$engine)
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, "Engine not found", BorhanBatchJobStatus::FAILED);
		}
		
		$this->impersonate($job->partnerId);
		$finished = $engine->close($job, $data);
		$this->unimpersonate();
		
		if(!$finished)
		{
			return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::ALMOST_DONE, $data);
		}
		
		return $this->closeJob($job, null, null, "Integrated", BorhanBatchJobStatus::FINISHED, $data);
	}

	/**
	 * @param BorhanIntegrationProviderType $type
	 * @return KIntegrationCloserEngine
	 */
	protected function getEngine($type)
	{
		return BorhanPluginManager::loadObject('KIntegrationCloserEngine', $type);
	}
}

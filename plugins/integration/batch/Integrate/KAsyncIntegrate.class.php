<?php
/**
 * @package plugins.integration
 * @subpackage Scheduler
 */
class KAsyncIntegrate extends KJobHandlerWorker
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
		return $this->integrate($job, $job->data);
	}
	
	protected function integrate(BorhanBatchJob $job, BorhanIntegrationJobData $data)
	{
		$engine = $this->getEngine($job->jobSubType);
		if(!$engine)
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, "Engine not found", BorhanBatchJobStatus::FAILED);
		}
		
		$this->impersonate($job->partnerId);
		$finished = $engine->dispatch($job, $data);
		$this->unimpersonate();
		
		if(!$finished)
		{
			return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::ALMOST_DONE, $data);
		}
		
		return $this->closeJob($job, null, null, "Integrated", BorhanBatchJobStatus::FINISHED, $data);
	}

	/**
	 * @param BorhanIntegrationProviderType $type
	 * @return KIntegrationEngine
	 */
	protected function getEngine($type)
	{
		return BorhanPluginManager::loadObject('KIntegrationEngine', $type);
	}
}

<?php
/**
 * Closes the process of provisioning a new stream.
 *
 * 
 * @package Scheduler
 * @subpackage Provision
 */
class KAsyncProvisionProvideCloser extends KJobCloserWorker
{
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job) {
		$this->closeProvisionProvide($job);
		
	}

	public static function getType()
	{
		return BorhanBatchJobType::PROVISION_PROVIDE;
	}

	protected function closeProvisionProvide (BorhanBatchJob $job)
	{
		if(($job->queueTime + self::$taskConfig->params->maxTimeBeforeFail) < time())
			return new KProvisionEngineResult(BorhanBatchJobStatus::CLOSER_TIMEOUT, "Timed out");
			
		$engine = KProvisionEngine::getInstance( $job->jobSubType, $job->data);
		if ( $engine == null )
		{
			$err = "Cannot find provision engine [{$job->jobSubType}] for job id [{$job->id}]";
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, $err, BorhanBatchJobStatus::FAILED);
		}
		
		BorhanLog::info( "Using engine: " . $engine->getName() );
	
		$results = $engine->checkProvisionedStream($job, $job->data);

		if($results->status == BorhanBatchJobStatus::FINISHED)
			return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::FINISHED, $results->data);
		
		return $this->closeJob($job, null, null, $results->errMessage, BorhanBatchJobStatus::ALMOST_DONE, $results->data);
		
	}
	
}
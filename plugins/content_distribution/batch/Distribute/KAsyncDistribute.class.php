<?php
/**
 * Distributes borhan entries to remote destination  
 *
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
abstract class KAsyncDistribute extends KJobHandlerWorker
{
	/**
	 * @var IDistributionEngine
	 */
	protected $engine;
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->distribute($job, $job->data);;
	}
	
	/**
	 * @return DistributionEngine
	 */
	abstract protected function getDistributionEngine($providerType, BorhanDistributionJobData $data);
	
	/**
	 * Throw detailed exceptions for any failure 
	 * @return bool true if job is closed, false for almost done
	 */
	abstract protected function execute(BorhanDistributionJobData $data);
	
	protected function distribute(BorhanBatchJob $job, BorhanDistributionJobData $data)
	{
		try
		{
			$this->engine = $this->getDistributionEngine($job->jobSubType, $data);
			if (!$this->engine)
			{
				BorhanLog::err('Cannot create DistributeEngine of type ['.$job->jobSubType.']');
				$this->closeJob($job, BorhanBatchJobErrorTypes::APP, null, 'Error: Cannot create DistributeEngine of type ['.$job->jobSubType.']', BorhanBatchJobStatus::FAILED);
				return $job;
			}
			$job = $this->updateJob($job, "Engine found [" . get_class($this->engine) . "]", BorhanBatchJobStatus::QUEUED);
						
			$closed = $this->execute($data);
			if($closed)
				return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::FINISHED, $data);
			 			
			return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::ALMOST_DONE, $data);
		}
		catch(BorhanDistributionException $ex)
		{
			BorhanLog::err($ex);
			$job = $this->closeJob($job, BorhanBatchJobErrorTypes::APP, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::RETRY, $job->data);
		}
		catch(Exception $ex)
		{
			BorhanLog::err($ex);
			$job = $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED, $job->data);
		}
		return $job;
	}
}

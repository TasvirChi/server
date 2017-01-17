<?php
/**
 * Base class for all job closer workers.
 * 
 * @package Scheduler
 */
abstract class KJobCloserWorker extends KJobHandlerWorker
{
	public function run($jobs = null)
	{
		if(KBatchBase::$taskConfig->isInitOnly())
			return $this->init();
		
		if(is_null($jobs))
			$jobs = KBatchBase::$kClient->batch->getExclusiveAlmostDone($this->getExclusiveLockKey(), KBatchBase::$taskConfig->maximumExecutionTime, $this->getMaxJobsEachRun(), $this->getFilter(), static::getType());
		
		BorhanLog::info(count($jobs) . " jobs to close");
		
		if(! count($jobs) > 0)
		{
			BorhanLog::info("Queue size: 0 sent to scheduler");
			$this->saveSchedulerQueue(static::getType());
			return null;
		}
		
		foreach($jobs as &$job)
		{
			try
			{
				self::setCurrentJob($job);
				$job = $this->exec($job);
			}
			catch(BorhanException $kex)
			{
				KBatchBase::unimpersonate();
				$job = $this->closeJob($job, BorhanBatchJobErrorTypes::BORHAN_API, $kex->getCode(), "Error: " . $kex->getMessage(), BorhanBatchJobStatus::FAILED);
			}
			catch(BorhanClientException $kcex)
			{
				KBatchBase::unimpersonate();
				$job = $this->closeJob($job, BorhanBatchJobErrorTypes::BORHAN_CLIENT, $kcex->getCode(), "Error: " . $kcex->getMessage(), BorhanBatchJobStatus::RETRY);
			}
			catch(Exception $ex)
			{
				KBatchBase::unimpersonate();
				$job = $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED);
			}
			self::unsetCurrentJob();
		}
			
		return $jobs;
	}
	
	/**
	* @param string $jobType
	* @param boolean $isCloser
	* @return BorhanWorkerQueueFilter
	*/
	protected function getQueueFilter($jobType)
	{
		$workerQueueFilter = $this->getBaseQueueFilter($jobType);
		$workerQueueFilter->filter->statusEqual = BorhanBatchJobStatus::ALMOST_DONE;
		
		return $workerQueueFilter;
	}
}

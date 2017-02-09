<?php
/**
 * Base class for all job handler workers.
 * 
 * @package Scheduler
 */
abstract class KJobHandlerWorker extends KBatchBase
{
	/**
	 * The job object that currently handled
	 * @var BorhanBatchJob
	 */
	private static $currentJob;
	
	/**
	 * @param BorhanBatchJob $job
	 * @return BorhanBatchJob
	 */
	abstract protected function exec(BorhanBatchJob $job);

	/**
	 * Returns the job object that currently handled
	 * @return BorhanBatchJob
	 */
	public static function getCurrentJob()
	{
		return self::$currentJob;
	}

	/**
	 * @param BorhanBatchJob $currentJob
	 */
	protected static function setCurrentJob(BorhanBatchJob $currentJob)
	{
		BorhanLog::debug("Start job[$currentJob->id] type[$currentJob->jobType] sub-type[$currentJob->jobSubType] object[$currentJob->jobObjectType] object-id[$currentJob->jobObjectId] partner-id[$currentJob->partnerId] dc[$currentJob->dc] parent-id[$currentJob->parentJobId] root-id[$currentJob->rootJobId]");
		self::$currentJob = $currentJob;
		
		self::$kClient->setClientTag(self::$clientTag . " partnerId: " . $currentJob->partnerId);
	}

	protected static function unsetCurrentJob()
	{
		$currentJob = self::getCurrentJob();
		BorhanLog::debug("End job[$currentJob->id]");
		self::$currentJob = null;

		self::$kClient->setClientTag(self::$clientTag);
	}
	
	protected function init()
	{
		$this->saveQueueFilter(static::getType());
	}
	
	protected function getMaxJobsEachRun()
	{
		if(!KBatchBase::$taskConfig->maxJobsEachRun)
			return 1;
		
		return KBatchBase::$taskConfig->maxJobsEachRun;
	}
	
	protected function getJobs()
	{
		$maxJobToPull = KBatchBase::$taskConfig->maxJobToPullToCache;
		return KBatchBase::$kClient->batch->getExclusiveJobs($this->getExclusiveLockKey(), KBatchBase::$taskConfig->maximumExecutionTime, 
				$this->getMaxJobsEachRun(), $this->getFilter(), static::getType(), $maxJobToPull);
	}
	
	public function run($jobs = null)
	{
		if(KBatchBase::$taskConfig->isInitOnly())
			return $this->init();
		
		if(is_null($jobs))
		{
			try
			{
				$jobs = $this->getJobs();
			}
			catch (Exception $e)
			{
				BorhanLog::err($e->getMessage());
				return null;
			}
		}
		
		BorhanLog::info(count($jobs) . " jobs to handle");
		
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
				self::unimpersonate();
			}
			catch(BorhanException $kex)
			{
				self::unimpersonate();
				$this->closeJobOnError($job,BorhanBatchJobErrorTypes::BORHAN_API, $kex, BorhanBatchJobStatus::FAILED);
			}
			catch(kApplicativeException $baex)
			{
				self::unimpersonate();
				$this->closeJobOnError($job,BorhanBatchJobErrorTypes::APP, $baex, BorhanBatchJobStatus::FAILED);
			}
			catch(kTemporaryException $ktex)
			{
				self::unimpersonate();
				if($ktex->getResetJobExecutionAttempts())
					KBatchBase::$kClient->batch->resetJobExecutionAttempts($job->id, $this->getExclusiveLockKey(), $job->jobType);
				
				$this->closeJobOnError($job,BorhanBatchJobErrorTypes::RUNTIME, $ktex, BorhanBatchJobStatus::RETRY, $ktex->getData());
			}
			catch(BorhanClientException $kcex)
			{
				self::unimpersonate();
				$this->closeJobOnError($job,BorhanBatchJobErrorTypes::BORHAN_CLIENT, $kcex, BorhanBatchJobStatus::RETRY);
			}
			catch(Exception $ex)
			{
				self::unimpersonate();
				$this->closeJobOnError($job,BorhanBatchJobErrorTypes::RUNTIME, $ex, BorhanBatchJobStatus::FAILED);
			}
			self::unsetCurrentJob();
		}
			
		return $jobs;
	}
	
	protected function closeJobOnError($job, $error, $ex, $status, $data = null)
	{
		try
		{
			self::unimpersonate();
			$job = $this->closeJob($job, $error, $ex->getCode(), "Error: " . $ex->getMessage(), $status, $data);
		} 
		catch(Exception $ex)
		{
			BorhanLog::err("Failed to close job after expirencing an error.");
			BorhanLog::err($ex->getMessage());
		}
	}
	
	/**
	 * @param int $jobId
	 * @param BorhanBatchJob $job
	 * @return BorhanBatchJob
	 */
	protected function updateExclusiveJob($jobId, BorhanBatchJob $job)
	{
		return KBatchBase::$kClient->batch->updateExclusiveJob($jobId, $this->getExclusiveLockKey(), $job);
	}
	
	/**
	 * @param BorhanBatchJob $job
	 * @return BorhanBatchJob
	 */
	protected function freeExclusiveJob(BorhanBatchJob $job)
	{
		$resetExecutionAttempts = false;
		if ($job->status == BorhanBatchJobStatus::ALMOST_DONE)
			$resetExecutionAttempts = true;
		
		$response = KBatchBase::$kClient->batch->freeExclusiveJob($job->id, $this->getExclusiveLockKey(), static::getType(), $resetExecutionAttempts);
		
		if(is_numeric($response->queueSize)) {
			BorhanLog::info("Queue size: $response->queueSize sent to scheduler");
			$this->saveSchedulerQueue(static::getType(), $response->queueSize);
		}
		
		return $response->job;
	}
	
	/**
	 * @return BorhanBatchJobFilter
	 */
	protected function getFilter()
	{
		$filter = new BorhanBatchJobFilter();
		if(KBatchBase::$taskConfig->filter)
			$filter = KBatchBase::$taskConfig->filter;
		
		if (KBatchBase::$taskConfig->minCreatedAtMinutes && is_numeric(KBatchBase::$taskConfig->minCreatedAtMinutes))
		{
			$minCreatedAt = time() - (KBatchBase::$taskConfig->minCreatedAtMinutes * 60);
			$filter->createdAtLessThanOrEqual = $minCreatedAt;
		}
		
		return $filter;
	}
	
	/**
	 * @return BorhanExclusiveLockKey
	 */
	protected function getExclusiveLockKey()
	{
		$lockKey = new BorhanExclusiveLockKey();
		$lockKey->schedulerId = $this->getSchedulerId();
		$lockKey->workerId = $this->getId();
		$lockKey->batchIndex = $this->getIndex();
		
		return $lockKey;
	}
	
	/**
	 * @param BorhanBatchJob $job
	 */
	protected function onFree(BorhanBatchJob $job)
	{
		$this->onJobEvent($job, KBatchEvent::EVENT_JOB_FREE);
	}
	
	/**
	 * @param BorhanBatchJob $job
	 */
	protected function onUpdate(BorhanBatchJob $job)
	{
		$this->onJobEvent($job, KBatchEvent::EVENT_JOB_UPDATE);
	}
	
	/**
	 * @param BorhanBatchJob $job
	 * @param int $event_id
	 */
	protected function onJobEvent(BorhanBatchJob $job, $event_id)
	{
		$event = new KBatchEvent();
		
		$event->partner_id = $job->partnerId;
		$event->entry_id = $job->entryId;
		$event->bulk_upload_id = $job->bulkJobId;
		$event->batch_parant_id = $job->parentJobId;
		$event->batch_root_id = $job->rootJobId;
		$event->batch_status = $job->status;
		
		$this->onEvent($event_id, $event);
	}
	
	/**
	 * @param string $jobType
	 * @return BorhanWorkerQueueFilter
	 */
	protected function getBaseQueueFilter($jobType)
	{
		$filter = $this->getFilter();
		$filter->jobTypeEqual = $jobType;
		
		$workerQueueFilter = new BorhanWorkerQueueFilter();
		$workerQueueFilter->schedulerId = $this->getSchedulerId();
		$workerQueueFilter->workerId = $this->getId();
		$workerQueueFilter->filter = $filter;
		$workerQueueFilter->jobType = $jobType;
		
		return $workerQueueFilter;
	}
	
	/**
	 * @param string $jobType
	 * @param boolean $isCloser
	 * @return BorhanWorkerQueueFilter
	 */
	protected function getQueueFilter($jobType)
	{
		$workerQueueFilter = $this->getBaseQueueFilter($jobType);
		//$workerQueueFilter->filter->statusIn = BorhanBatchJobStatus::PENDING . ',' . BorhanBatchJobStatus::RETRY;
		
		return $workerQueueFilter;
	}
	
	/**
	 * @param int $jobType
	 */
	protected function saveQueueFilter($jobType)
	{
		$filter = $this->getQueueFilter($jobType);
		
		$type = KBatchBase::$taskConfig->name;
		$file = "$type.flt";
		
		KScheduleHelperManager::saveFilter($file, $filter);
	}
	
	/**
	 * @param int $jobType
	 * @param int $size
	 */
	protected function saveSchedulerQueue($jobType, $size = null)
	{
		if(is_null($size))
		{
			$workerQueueFilter = $this->getQueueFilter($jobType);
			$size = KBatchBase::$kClient->batch->getQueueSize($workerQueueFilter);
		}
		
		$queueStatus = new BorhanBatchQueuesStatus();
		$queueStatus->workerId = $this->getId();
		$queueStatus->jobType = $jobType;
		$queueStatus->size = $size;
		
		$this->saveSchedulerCommands(array($queueStatus));
	}
	
	/**
	 * @return BorhanBatchJob
	 */
	protected function newEmptyJob()
	{
		return new BorhanBatchJob();
	}
	
	/**
	 * @param BorhanBatchJob $job
	 * @param string $msg
	 * @param int $status
	 * @param unknown_type $data
	 * @param boolean $remote
	 * @return BorhanBatchJob
	 */
	protected function updateJob(BorhanBatchJob $job, $msg, $status, BorhanJobData $data = null)
	{
		$updateJob = $this->newEmptyJob();
		
		if(! is_null($msg))
		{
			$updateJob->message = $msg;
			$updateJob->description = $job->description . "\n$msg";
		}
		
		$updateJob->status = $status;
		$updateJob->data = $data;
		
		BorhanLog::info("job[$job->id] status: [$status] msg : [$msg]");
		if($this->isUnitTest)
			return $job;
		
		$job = $this->updateExclusiveJob($job->id, $updateJob);
		if($job instanceof BorhanBatchJob)
			$this->onUpdate($job);
		
		return $job;
	}
	
	/**
	 * @param BorhanBatchJob $job
	 * @param int $errType
	 * @param int $errNumber
	 * @param string $msg
	 * @param int $status
	 * @param BorhanJobData $data
	 * @return BorhanBatchJob
	 */
	protected function closeJob(BorhanBatchJob $job, $errType, $errNumber, $msg, $status, $data = null)
	{
		if(! is_null($errType))
			BorhanLog::err($msg);
		
		$updateJob = $this->newEmptyJob();
		
		if(! is_null($msg))
		{
			$updateJob->message = $msg;
			$updateJob->description = $job->description . "\n$msg";
		}
		
		$updateJob->status = $status;
		$updateJob->errType = $errType;
		$updateJob->errNumber = $errNumber;
		$updateJob->data = $data;
		
		BorhanLog::info("job[$job->id] status: [$status] msg : [$msg]");
		if($this->isUnitTest)
		{
			$job->status = $updateJob->status;
			$job->message = $updateJob->message;
			$job->description = $updateJob->description;
			$job->errType = $updateJob->errType;
			$job->errNumber = $updateJob->errNumber;
			return $job;
		}
		
		$job = $this->updateExclusiveJob($job->id, $updateJob);
		if($job instanceof BorhanBatchJob)
			$this->onUpdate($job);
		
		BorhanLog::info("Free job[$job->id]");
		$job = $this->freeExclusiveJob($job);
		if($job instanceof BorhanBatchJob)
			$this->onFree($job);
		
		return $job;		
	}
}

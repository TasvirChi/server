<?php
/**
 * @package Scheduler
 * @subpackage Bulk-Upload
 */

setlocale ( LC_ALL, 'en_US.UTF-8' );

/**
 * Will initiate a single bulk upload.
 * The state machine of the job is as follows:
 * get the csv, parse it and validate it
 * creates the entries
 *
 * @package Scheduler
 * @subpackage Bulk-Upload
 */
class KAsyncBulkUpload extends KJobHandlerWorker 
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::BULKUPLOAD;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		ini_set('auto_detect_line_endings', true);
		try 
		{
			$job = $this->startBulkUpload($job);
		}
		catch (BorhanBulkUploadAbortedException $abortedException)
		{
			self::unimpersonate();
			$job = $this->closeJob($job, null, null, null, BorhanBatchJobStatus::ABORTED);
		}
		catch(BorhanBatchException $kbex)
		{
			self::unimpersonate();
			$job = $this->closeJob($job, BorhanBatchJobErrorTypes::APP, $kbex->getCode(), "Error: " . $kbex->getMessage(), BorhanBatchJobStatus::FAILED);
		}
		catch(BorhanException $kex)
		{
			self::unimpersonate();
			$job = $this->closeJob($job, BorhanBatchJobErrorTypes::BORHAN_API, $kex->getCode(), "Error: " . $kex->getMessage(), BorhanBatchJobStatus::FAILED);
		}
		catch(BorhanClientException $kcex)
		{
			self::unimpersonate();
			$job = $this->closeJob($job, BorhanBatchJobErrorTypes::BORHAN_CLIENT, $kcex->getCode(), "Error: " . $kcex->getMessage(), BorhanBatchJobStatus::RETRY);
		}
		catch(Exception $ex)
		{
			self::unimpersonate();
			$job = $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED);
		}
		ini_set('auto_detect_line_endings', false);
		
		return $job;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::getMaxJobsEachRun()
	 */
	protected function getMaxJobsEachRun()
	{
		return 1;
	}
	
	/**
	 * 
	 * Starts the bulk upload
	 * @param BorhanBatchJob $job
	 */
	private function startBulkUpload(BorhanBatchJob $job)
	{
		BorhanLog::info( "Start bulk upload ($job->id)" );
		
		//Gets the right Engine instance 
		$engine = KBulkUploadEngine::getEngine($job->jobSubType, $job);
		if (is_null ( $engine )) {
			throw new BorhanException ( "Unable to find bulk upload engine", BorhanBatchJobAppErrors::ENGINE_NOT_FOUND );
		}
		$job = $this->updateJob($job, 'Parsing file [' . $engine->getName() . ']', BorhanBatchJobStatus::QUEUED, $engine->getData());
		
		$engine->setJob($job);
		$engine->setData($job->data);
		$engine->handleBulkUpload();
		
		$job = $engine->getJob();
		$data = $engine->getData();

		$countObjects = $this->countCreatedObjects($job->id, $job->data->bulkUploadObjectType);
		$countHandledObjects = $countObjects[0];
		$countErrorObjects = $countObjects[1];

		if(!$countHandledObjects && !$engine->shouldRetry() && $countErrorObjects)
			throw new BorhanBatchException("None of the uploaded items were processed succsessfuly", BorhanBatchJobAppErrors::BULK_NO_ENTRIES_HANDLED, $engine->getData());
		
		if($engine->shouldRetry())
		{
			self::$kClient->batch->resetJobExecutionAttempts($job->id, $this->getExclusiveLockKey(), $job->jobType);
			return $this->closeJob($job, null, null, "Retrying: ".$countHandledObjects." ".$engine->getObjectTypeTitle()." objects were handled untill now", BorhanBatchJobStatus::RETRY);
		}
			
		return $this->closeJob($job, null, null, 'Waiting for objects closure', BorhanBatchJobStatus::ALMOST_DONE, $data);
	}
	
	/**
	 * Return the count of created entries
	 * @param int $jobId
	 * @return int
	 */
	protected function countCreatedObjects($jobId, $bulkuploadObjectType) 
	{
		$createdCount = 0;
		$errorCount = 0;
		
		$counters = self::$kClient->batch->countBulkUploadEntries($jobId, $bulkuploadObjectType);
		foreach($counters as $counter)
		{
			/** @var BorhanKeyValue $counter */
			if ($counter->key == 'created')
				$createdCount = $counter->value;
			if ($counter->key == 'error')
				$errorCount = $counter->value;
		}
		
		return array($createdCount, $errorCount);
	}
	
}

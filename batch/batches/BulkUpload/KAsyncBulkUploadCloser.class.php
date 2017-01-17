<?php
/**
 * @package Scheduler
 * @subpackage Bulk-Upload
 */

/**
 * Will close almost done bulk uploads.
 * The state machine of the job is as follows:
 * 	 	get almost done bulk uploads 
 * 		check the imports and converts statuses
 * 		update the bulk status
 *
 * @package Scheduler
 * @subpackage Bulk-Upload
 */
class KAsyncBulkUploadCloser extends KJobCloserWorker
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
		return $this->fetchStatus($job);
	}
	
	private function fetchStatus(BorhanBatchJob $job)
	{
		if(($job->queueTime + self::$taskConfig->params->maxTimeBeforeFail) < time())
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::CLOSER_TIMEOUT, 'Timed out', BorhanBatchJobStatus::FAILED);
			
		$openedEntries = self::$kClient->batch->updateBulkUploadResults($job->id);
		$job = $this->updateJob($job, "Unclosed entries remaining: $openedEntries" , BorhanBatchJobStatus::ALMOST_DONE);
		if(!$openedEntries)
		{
		    $numOfObjects = $job->data->numOfObjects;
		    $numOfErrorObjects = $job->data->numOfErrorObjects;
		    BorhanLog::info("numOfSuccessObjects: $numOfObjects, numOfErrorObjects: $numOfErrorObjects");
		    
		    if ($numOfErrorObjects == 0)
		    {
			    return $this->closeJob($job, null, null, 'Finished successfully', BorhanBatchJobStatus::FINISHED);
		    }
		    else if($numOfObjects > 0) //some objects created successfully
		    {
		    	return $this->closeJob($job, null, null, 'Finished, but with some errors', BorhanBatchJobStatus::FINISHED_PARTIALLY);
		    }
		    else
		    {
		        return $this->closeJob($job, null, null, 'Failed to create objects', BorhanBatchJobStatus::FAILED);
		    }
		}	
		return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::ALMOST_DONE);
	}
}

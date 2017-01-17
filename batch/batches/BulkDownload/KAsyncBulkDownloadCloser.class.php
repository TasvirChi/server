<?php
/**
 * @package Scheduler
 * @subpackage Bulk-Download
 */

/**
 * Will close almost done bulk downloads.
 * The state machine of the job is as follows:
 * 	 	get almost done bulk downloads 
 * 		check converts statuses
 * 		update the bulk status
 *
 * @package Scheduler
 * @subpackage Bulk-Download
 */
class KAsyncBulkDownloadCloser extends KJobCloserWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::BULKDOWNLOAD;
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
		if(($job->queueTime + KBatchBase::$taskConfig->params->maxTimeBeforeFail) < time())
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::CLOSER_TIMEOUT, 'Timed out', BorhanBatchJobStatus::FAILED);
		
		return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::ALMOST_DONE);
	}
}

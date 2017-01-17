<?php
/**
 * This worker deletes physical files from disk
 *
 * @package Scheduler
 * @subpackage Delete
 */
class KAsyncDeleteFile extends KJobHandlerWorker
{
	public static function getType()
	{
		return BorhanBatchJobType::DELETE_FILE;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		$this->updateJob($job, "File deletion started", BorhanBatchJobStatus::PROCESSING);
		$jobData = $job->data;
		
		/* @var $jobData BorhanDeleteFileJobData */
		$result = unlink($jobData->localFileSyncPath);
		
		if (!$result)
			return $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, null, "Failed to delete file from disk", BorhanBatchJobStatus::FAILED);
		
		return $this->closeJob($job, null, null, 'File deleted successfully', BorhanBatchJobStatus::FINISHED);
		
	}


}
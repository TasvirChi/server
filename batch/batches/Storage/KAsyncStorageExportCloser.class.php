<?php
class KAsyncStorageExportCloser extends KJobCloserWorker
{
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job) {
		$this->closeStorageExport($job);
		
	}

	public static function getType()
	{
		return BorhanBatchJobType::STORAGE_EXPORT;
	}
	
	protected function closeStorageExport (BorhanBatchJob $job)
	{
		$storageExportEngine = KExportEngine::getInstance($job->jobSubType, $job->partnerId, $job->data);
		
		$closeResult = $storageExportEngine->verifyExportedResource();
		$this->closeJob($job, null, null, null, $closeResult ? BorhanBatchJobStatus::FINISHED : BorhanBatchJobStatus::ALMOST_DONE);
	}
}
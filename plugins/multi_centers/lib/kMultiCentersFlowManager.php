<?php
/**
 * @package plugins.multiCenters
 * @subpackage lib
 */
class kMultiCentersFlowManager implements kBatchJobStatusEventConsumer
{
	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::shouldConsumeJobStatusEvent()
	 */
	public function shouldConsumeJobStatusEvent(BatchJob $dbBatchJob)
	{
		if($dbBatchJob->getJobType() == BatchJobType::FILESYNC_IMPORT)
			return true;
				
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::updatedJob()
	 */
	public function updatedJob(BatchJob $dbBatchJob, BatchJob $twinJob = null)
	{
		$dbBatchJob = $this->updatedFileSyncImport($dbBatchJob, $dbBatchJob->getData(), $twinJob);
		
		return true;
	}
	
		
	protected function updatedFileSyncImport(BatchJob $dbBatchJob, kFileSyncImportJobData $data, BatchJob $twinJob = null)
	{
		switch($dbBatchJob->getStatus())
		{
			// success
			case BatchJob::BATCHJOB_STATUS_FINISHED:
				return $this->updatedFileSyncImportFinished($dbBatchJob, $data, $twinJob);
				
			// failure
			case BatchJob::BATCHJOB_STATUS_ABORTED:
			case BatchJob::BATCHJOB_STATUS_FATAL:
			case BatchJob::BATCHJOB_STATUS_FAILED:
				return $this->updatedFileSyncImportFailed($dbBatchJob, $data, $twinJob);
			default:
				return $dbBatchJob;
		}
	}
	
	protected function updatedFileSyncImportFinished(BatchJob $dbBatchJob, kFileSyncImportJobData $data, BatchJob $twinJob = null)
	{
		// Update relevant filesync as READY
		$fileSyncId = $data->getFilesyncId();
		if (!$fileSyncId) {
			KalturaLog::err('File sync ID not found in job data.');
			throw new KalturaAPIException(MultiCentersErrors::INVALID_FILESYNC_ID);
		}
		$fileSync = FileSyncPeer::retrieveByPK($fileSyncId);
		if (!$fileSync) {
			KalturaLog::err("Invalid filesync record with id [$fileSyncId]");
			throw new KalturaAPIException(MultiCentersErrors::INVALID_FILESYNC_RECORD, $fileSyncId);
		}
		$fileSync->setStatus(FileSync::FILE_SYNC_STATUS_READY);
		$fileSync->setFileSizeFromPath(kFileSyncUtils::getLocalFilePathForKey(kFileSyncUtils::getKeyForFileSync($fileSync)));
		$fileSync->save();
		return $dbBatchJob;
	}
	
	protected function updatedFileSyncImportFailed(BatchJob $dbBatchJob, kFileSyncImportJobData $data, BatchJob $twinJob = null)
	{
		// Update relevant filesync as FAILED
		$fileSyncId = $data->getFilesyncId();
		if (!$fileSyncId) {
			KalturaLog::err('File sync ID not found in job data.');
			throw new KalturaAPIException(MultiCentersErrors::INVALID_FILESYNC_ID);
		}
		$fileSync = FileSyncPeer::retrieveByPK($fileSyncId);
		if (!$fileSync) {
			KalturaLog::err("Invalid filesync record with id [$fileSyncId]");
			throw new KalturaAPIException(MultiCentersErrors::INVALID_FILESYNC_RECORD, $fileSyncId);
		}
		$fileSync->setStatus(FileSync::FILE_SYNC_STATUS_ERROR);
		$fileSync->save();
		return $dbBatchJob;
	}
	
	
}
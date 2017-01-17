<?php
/**
 * @package Scheduler
 * @subpackage Storage
 */

/**
 * Will export a single file to ftp or scp server 
 *
 * @package Scheduler
 * @subpackage Storage
 */
class KAsyncStorageExport extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::STORAGE_EXPORT;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->export($job, $job->data);
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::getMaxJobsEachRun()
	 */
	protected function getMaxJobsEachRun()
	{
		return 1;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::getFilter()
	 */
	protected function getFilter()
	{
		$filter = parent::getFilter();
		
		if(KBatchBase::$taskConfig->params)
		{
			if(KBatchBase::$taskConfig->params->minFileSize && is_numeric(KBatchBase::$taskConfig->params->minFileSize))
				$filter->fileSizeGreaterThan = KBatchBase::$taskConfig->params->minFileSize;
			
			if(KBatchBase::$taskConfig->params->maxFileSize && is_numeric(KBatchBase::$taskConfig->params->maxFileSize))
				$filter->fileSizeLessThan = KBatchBase::$taskConfig->params->maxFileSize;
		}
			
		return $filter;
	}
	
	/**
	 * Will take a single BorhanBatchJob and export the given file 
	 * 
	 * @param BorhanBatchJob $job
	 * @param BorhanStorageExportJobData $data
	 * @return BorhanBatchJob
	 */
	protected function export(BorhanBatchJob $job, BorhanStorageExportJobData $data)
	{
		$engine = KExportEngine::getInstance($job->jobSubType, $job->partnerId, $data);
		if(!$engine)
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, "Engine not found", BorhanBatchJobStatus::FAILED);
		}
		$this->updateJob($job, null, BorhanBatchJobStatus::QUEUED);
		$exportResult = $engine->export();

		return $this->closeJob($job, null , null, null, $exportResult ? BorhanBatchJobStatus::FINISHED : BorhanBatchJobStatus::ALMOST_DONE, $data );
	}
}

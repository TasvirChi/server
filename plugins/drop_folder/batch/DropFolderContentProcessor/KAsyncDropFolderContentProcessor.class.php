<?php

class KAsyncDropFolderContentProcessor extends KJobHandlerWorker
{
	/**
	 * @var BorhanDropFolderClientPlugin
	 */
	protected $dropFolderPlugin = null;
	
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::DROP_FOLDER_CONTENT_PROCESSOR;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		try 
		{
			return $this->process($job, $job->data);
		}
		catch(kTemporaryException $e)
		{
			$this->unimpersonate();
			if($e->getResetJobExecutionAttempts())
				throw $e;
			return $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $e->getCode(), "Error: " . $e->getMessage(), BorhanBatchJobStatus::FAILED);
		}
		catch(BorhanClientException $e)
		{
			$this->unimpersonate();
			return $this->closeJob($job, BorhanBatchJobErrorTypes::BORHAN_CLIENT, $e->getCode(), "Error: " . $e->getMessage(), BorhanBatchJobStatus::FAILED);
		}
	}

	protected function process(BorhanBatchJob $job, BorhanDropFolderContentProcessorJobData $data)
	{
		$job = $this->updateJob($job, "Start processing drop folder files [$data->dropFolderFileIds]", BorhanBatchJobStatus::QUEUED);
		$engine = KDropFolderEngine::getInstance($job->jobSubType);
		$engine->processFolder($job, $data);
		return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::FINISHED);
	}
		
}

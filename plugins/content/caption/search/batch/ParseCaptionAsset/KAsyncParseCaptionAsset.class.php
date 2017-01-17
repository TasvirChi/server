<?php
/**
 * @package plugins.captionSearch
 * @subpackage Scheduler
 */
class KAsyncParseCaptionAsset extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::PARSE_CAPTION_ASSET;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->parse($job, $job->data);
	}
	
	protected function parse(BorhanBatchJob $job, BorhanParseCaptionAssetJobData $data)
	{
		try
		{
			$this->updateJob($job, "Start parsing caption asset [$data->captionAssetId]", BorhanBatchJobStatus::QUEUED);
			
			$captionSearchPlugin = BorhanCaptionSearchClientPlugin::get(self::$kClient);
			$captionSearchPlugin->captionAssetItem->parse($data->captionAssetId);
			
			$this->closeJob($job, null, null, "Finished parsing", BorhanBatchJobStatus::FINISHED);
		}
		catch(Exception $ex)
		{
			$this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED, $data);
		}
		return $job;
	}
}

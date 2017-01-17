<?php
/**
 * @package plugins.tagSearch
 * @subpackage Scheduler
 */
class KAsyncTagIndex extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job) {
		
		$this->reIndexTags($job);
		
	}

	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::INDEX_TAGS;
	}
	
	protected function reIndexTags (BorhanBatchJob $job)
	{
		BorhanLog::info("Re-indexing tags according to privacy contexts");
		$tagPlugin = BorhanTagSearchClientPlugin::get(self::$kClient);
		$this->impersonate($job->partnerId);
		try 
		{
			$tagPlugin->tag->indexCategoryEntryTags($job->data->changedCategoryId, $job->data->deletedPrivacyContexts, $job->data->addedPrivacyContexts);
		}
		catch (Exception $e)
		{
			$this->unimpersonate();
			return $this->closeJob($job, BorhanBatchJobErrorTypes::BORHAN_API, $e->getCode(), $e->getMessage(), BorhanBatchJobStatus::FAILED);
		}
		$this->unimpersonate();
		return $this->closeJob($job, null, null, "Re-index complete", BorhanBatchJobStatus::FINISHED);
		
	}
}
<?php
/**
 * @package Scheduler
 * @subpackage MoveCategoryEntries
 */

/**
 * Will sync category privacy context on category entries
 *
 * @package Scheduler
 * @subpackage SyncCategoryPrivacyContext
 */
class KAsyncSyncCategoryPrivacyContext extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::SYNC_CATEGORY_PRIVACY_CONTEXT;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->syncPrivacyContext($job, $job->data);
	}
	
	/**
	 * sync category privacy context on category entries
	 * 
	 * @param BorhanBatchJob $job
	 * @param BorhanSyncCategoryPrivacyContextJobData $data
	 * 
	 * @return BorhanBatchJob
	 */
	protected function syncPrivacyContext(BorhanBatchJob $job, BorhanSyncCategoryPrivacyContextJobData $data)
	{
	    KBatchBase::impersonate($job->partnerId);
	    
	    $this->syncCategoryPrivacyContext($job, $data, $data->categoryId);
		
		KBatchBase::unimpersonate();
		
		$job = $this->closeJob($job, null, null, null, BorhanBatchJobStatus::FINISHED);
		
		return $job;
	}
	
	private function syncCategoryPrivacyContext(BorhanBatchJob $job, BorhanSyncCategoryPrivacyContextJobData $data, $categoryId)
	{
			    
		$categoryEntryPager = $this->getFilterPager();
	    $categoryEntryFilter = new BorhanCategoryEntryFilter();
		$categoryEntryFilter->orderBy = BorhanCategoryEntryOrderBy::CREATED_AT_ASC;
		$categoryEntryFilter->categoryIdEqual = $categoryId;
		if($data->lastUpdatedCategoryEntryCreatedAt)
			$categoryEntryFilter->createdAtGreaterThanOrEqual = $data->lastUpdatedCategoryEntryCreatedAt;		
		$categoryEntryList = KBatchBase::$kClient->categoryEntry->listAction($categoryEntryFilter, $categoryEntryPager);
		
		while(count($categoryEntryList->objects))
		{
			KBatchBase::$kClient->startMultiRequest();
			foreach ($categoryEntryList->objects as $categoryEntry) 
			{
				KBatchBase::$kClient->categoryEntry->syncPrivacyContext($categoryEntry->entryId, $categoryEntry->categoryId);				
			}

			KBatchBase::$kClient->doMultiRequest();	
			$data->lastUpdatedCategoryEntryCreatedAt = $categoryEntry->createdAt;
			$categoryEntryPager->pageIndex++;
			
			KBatchBase::unimpersonate();
			$this->updateJob($job, null, BorhanBatchJobStatus::PROCESSING, $data);
			KBatchBase::impersonate($job->partnerId);
							
			$categoryEntryList = KBatchBase::$kClient->categoryEntry->listAction($categoryEntryFilter, $categoryEntryPager);
		}
	}
		
	private function getFilterPager()
	{
		$pager = new BorhanFilterPager();
		$pager->pageSize = 100;
		if(KBatchBase::$taskConfig->params->pageSize)
			$pager->pageSize = KBatchBase::$taskConfig->params->pageSize;
		return $pager;
	}
}

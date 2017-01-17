<?php
/**
 * @package Scheduler
 * @subpackage Index
 */

/**
 * Will index objects in the indexing server
 * according to the suppolied engine type and filter 
 *
 * @package Scheduler
 * @subpackage Index
 */
class KAsyncIndex extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::INDEX;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->indexObjects($job, $job->data);
	}
	
	/**
	 * Will take a single filter and call each item to be indexed 
	 */
	private function indexObjects(BorhanBatchJob $job, BorhanIndexJobData $data)
	{
		$engine = KIndexingEngine::getInstance($job->jobSubType);
		$engine->configure($job->partnerId);
	
		$filter = clone $data->filter;
		$advancedFilter = new BorhanIndexAdvancedFilter();

		$this->initAdvancedFilter($advancedFilter,$data);

		$filter->advancedSearch = $advancedFilter;

		$continue = true;
		while($continue)
		{
			$indexedObjectsCount = $engine->run($filter, $data->shouldUpdate);
			$continue = (bool) $indexedObjectsCount;
			$lastIndexId = $engine->getLastIndexId();
			$lastIndexDepth = $engine->getLastIndexDepth();
			
			$data->lastIndexId = $lastIndexId;
			$data->lastIndexDepth = $lastIndexDepth;
			$this->updateJob($job, "Indexed $indexedObjectsCount objects", BorhanBatchJobStatus::PROCESSING, $data);
			
			$advancedFilter->indexIdGreaterThan = $lastIndexId;
			$advancedFilter->depthGreaterThanEqual = $lastIndexDepth;
			$filter->advancedSearch = $advancedFilter;
		}
		
		return $this->closeJob($job, null, null, "Index objects finished", BorhanBatchJobStatus::FINISHED);
	}

	private function initAdvancedFilter(&$advancedFilter , &$data)
	{
		if($data->lastIndexId)
			$advancedFilter->indexIdGreaterThan = $data->lastIndexId;
		if($data->lastIndexDepth)
			$advancedFilter->depthGreaterThanEqual = $data->lastIndexDepth;
	}

}

<?php
/**
 * Will delete objects in the deleting server
 * according to the suppolied engine type and filter 
 *
 * @package Scheduler
 * @subpackage Delete
 */
class KAsyncDelete extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::DELETE;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->deleteObjects($job, $job->data);
	}
	
	/**
	 * Will take a single filter and call each item to be deleted 
	 */
	private function deleteObjects(BorhanBatchJob $job, BorhanDeleteJobData $data)
	{
		$engine = KDeletingEngine::getInstance($job->jobSubType);
		$engine->configure($job->partnerId, $data);
	
		$filter = clone $data->filter;
		
		$continue = true;
		while($continue)
		{
			$deletedObjectsCount = $engine->run($filter);
			$continue = (bool) $deletedObjectsCount;
		}
		
		return $this->closeJob($job, null, null, "Delete objects finished", BorhanBatchJobStatus::FINISHED);
	}
}

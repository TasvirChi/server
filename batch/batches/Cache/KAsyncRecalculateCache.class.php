<?php
/**
 * @package Scheduler
 * @subpackage RecalculateCache
 */

/**
 * Will recalculate cached objects 
 *
 * @package Scheduler
 * @subpackage RecalculateCache
 */
class KAsyncRecalculateCache extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::RECALCULATE_CACHE;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->recalculate($job, $job->data);
	}
	
	private function recalculate(BorhanBatchJob $job, BorhanRecalculateCacheJobData $data)
	{
		$engine = KRecalculateCacheEngine::getInstance($job->jobSubType);
		$recalculatedObjects = $engine->recalculate($data);
		return $this->closeJob($job, null, null, "Recalculated $recalculatedObjects cache objects", BorhanBatchJobStatus::FINISHED);
	}
}

<?php
/**
 * @package Scheduler
 * @subpackage TagResolver
 */
class KAsyncTagResolve extends KPeriodicWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::run()
	 */
	public function run($jobs = null) 
	{
		$tagPlugin = BorhanTagSearchClientPlugin::get(self::$kClient);
		$deletedTags = $tagPlugin->tag->deletePending();
		
		BorhanLog::info("Finished resolving tags: $deletedTags tags removed from DB");
	}
	
	/**
	 * @return int
	 * @throws Exception
	 */
	public static function getType()
	{
		return BorhanBatchJobType::TAG_RESOLVE;
	}

	
}
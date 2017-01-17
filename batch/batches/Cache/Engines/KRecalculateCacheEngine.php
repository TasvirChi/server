<?php
/**
 * @package Scheduler
 * @subpackage RecalculateCache
 */
abstract class KRecalculateCacheEngine
{
	/**
	 * @param int $objectType of enum BorhanRecalculateCacheType
	 * @return KRecalculateCacheEngine
	 */
	public static function getInstance($objectType)
	{
		switch($objectType)
		{
			case BorhanRecalculateCacheType::RESPONSE_PROFILE:
				return new KRecalculateResponseProfileCacheEngine();
				
			default:
				return BorhanPluginManager::loadObject('KRecalculateCacheEngine', $objectType);
		}
	}
	
	/**
	 * @param BorhanRecalculateCacheJobData $data
	 * @return int cached objects count
	 */
	abstract public function recalculate(BorhanRecalculateCacheJobData $data);
}

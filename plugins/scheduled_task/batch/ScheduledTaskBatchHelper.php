<?php

class ScheduledTaskBatchHelper
{
	/**
	 * @param BorhanClient $client
	 * @param BorhanScheduledTaskProfile $scheduledTaskProfile
	 * @param BorhanFilterPager $pager
	 * @return BorhanObjectListResponse
	 */
	public static function query(BorhanClient $client, BorhanScheduledTaskProfile $scheduledTaskProfile, BorhanFilterPager $pager)
	{
		$objectFilterEngineType = $scheduledTaskProfile->objectFilterEngineType;
		$objectFilterEngine = KObjectFilterEngineFactory::getInstanceByType($objectFilterEngineType, $client);
		$objectFilterEngine->setPageSize($pager->pageSize);
		$objectFilterEngine->setPageIndex($pager->pageIndex);
		return $objectFilterEngine->query($scheduledTaskProfile->objectFilter);
	}
}
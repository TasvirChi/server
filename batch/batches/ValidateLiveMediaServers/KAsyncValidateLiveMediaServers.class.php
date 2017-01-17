<?php
/**
 * @package Scheduler
 * @subpackage ValidateLiveMediaServers
 */

/**
 * Validates periodically that all live entries are still broadcasting to the connected media servers
 *
 * @package Scheduler
 * @subpackage ValidateLiveMediaServers
 */
class KAsyncValidateLiveMediaServers extends KPeriodicWorker
{
	const ENTRY_SERVER_NODE_MIN_CREATION_TIMEE = 120;
	
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::CLEANUP;
	}
	
	/* (non-PHPdoc)
	 * @see KBatchBase::run()
	*/
	public function run($jobs = null)
	{
		$entryServerNodeMinCreationTime = $this->getAdditionalParams("minCreationTime");
		if(!$entryServerNodeMinCreationTime)
			$entryServerNodeMinCreationTime = self::ENTRY_SERVER_NODE_MIN_CREATION_TIMEE;
		
		$entryServerNodeFilter = new BorhanEntryServerNodeFilter();
		$entryServerNodeFilter->orderBy = BorhanEntryServerNodeOrderBy::CREATED_AT_ASC;
		$entryServerNodeFilter->createdAtLessThanOrEqual = time() - $entryServerNodeMinCreationTime;
		
		$entryServerNodeFilter->statusIn = BorhanEntryServerNodeStatus::PLAYABLE . ',' . 
				BorhanEntryServerNodeStatus::BROADCASTING . ',' .
				BorhanEntryServerNodeStatus::AUTHENTICATED . ',' .
				BorhanEntryServerNodeStatus::MARKED_FOR_DELETION;
		
		$entryServerNodePager = new BorhanFilterPager();
		$entryServerNodePager->pageSize = 500;
		$entryServerNodePager->pageIndex = 1;
		
		$entryServerNodes = self::$kClient->entryServerNode->listAction($entryServerNodeFilter, $entryServerNodePager);
		while(count($entryServerNodes->objects))
		{
			foreach($entryServerNodes->objects as $entryServerNode)
			{
				try
				{
					/* @var $entryServerNode BorhanEntryServerNode */
					self::impersonate($entryServerNode->partnerId);
					self::$kClient->entryServerNode->validateRegisteredEntryServerNode($entryServerNode->id);
					self::unimpersonate();
				}
				catch (BorhanException $e)
				{
					self::unimpersonate();
					BorhanLog::err("Caught exception with message [" . $e->getMessage()."]");
				}
			}
			
			$entryServerNodePager->pageIndex++;
			$entryServerNodes = self::$kClient->entryServerNode->listAction($entryServerNodeFilter, $entryServerNodePager);
		}
	}
}

<?php
/**
 * @package Scheduler
 * @subpackage ClearCuePoints
 */

/**
 * Clear cue points from live entries that were not marked as handled (cases were recording is off)
 *
 * @package Scheduler
 * @subpackage ClearCuePoints
 */
class KAsyncClearCuePoints extends KPeriodicWorker
{	
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
		$entryFilter = new BorhanLiveStreamEntryFilter();
		$entryFilter->isLive = BorhanNullableBoolean::TRUE_VALUE;
		$entryFilter->orderBy = BorhanLiveStreamEntryOrderBy::CREATED_AT_ASC;
		
		$entryFilter->moderationStatusIn = 
			BorhanEntryModerationStatus::PENDING_MODERATION . ',' .
			BorhanEntryModerationStatus::APPROVED . ',' .
			BorhanEntryModerationStatus::REJECTED . ',' .
			BorhanEntryModerationStatus::FLAGGED_FOR_REVIEW . ',' .
			BorhanEntryModerationStatus::AUTO_APPROVED;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 100;
		$pager->pageIndex = 1;
		
		$entries = self::$kClient->liveStream->listAction($entryFilter, $pager);
		
		while(count($entries->objects))
		{
			foreach($entries->objects as $entry)
			{
				//When entry has recording on the cue poitns are copied from the live entry to the vod entry
				//The copy process allready markes the live entry cue points as handled
				/* @var $entry BorhanLiveEntry */
				if($entry->recordStatus !== BorhanRecordStatus::DISABLED)
					continue;
					
				$this->clearEntryCuePoints($entry);
			}
			
			$pager->pageIndex++;
			$entries = self::$kClient->liveStream->listAction($entryFilter, $pager);
		}
	}
	
	private function clearEntryCuePoints($entry)
	{
		$cuePointPlugin = BorhanCuePointClientPlugin::get(self::$kClient);
		
		$cuePointFilter = $this->getFilter("BorhanCuePointFilter");
		$cuePointFilter->entryIdEqual = $entry->id;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 100;
		
		$cuePoints = $cuePointPlugin->cuePoint->listAction($cuePointFilter, $pager);

		if(!$cuePoints->objects)
		{
			BorhanLog::debug("No cue points found for entry [{$entry->id}] continue to next live entry");
			return;
		}

		//Clear Max 100 cue points each run on each live entry to avoid massive old cue points updates
		self::impersonate($entry->partnerId);
		self::$kClient->startMultiRequest();
		foreach ($cuePoints->objects as $cuePoint)
		{
			$cuePointPlugin->cuePoint->updateStatus($cuePoint->id, BorhanCuePointStatus::HANDLED);
		}
		self::$kClient->doMultiRequest();
		self::unimpersonate();
	}
}

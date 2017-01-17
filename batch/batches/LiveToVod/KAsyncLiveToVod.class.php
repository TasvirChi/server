<?php
/**
 * @package Scheduler
 * @subpackage Copy
 */

/**
 * Will copy objects and add them
 * according to the suppolied engine type and filter 
 *
 * @package Scheduler
 * @subpackage Copy
 */
class KAsyncLiveToVod extends KJobHandlerWorker
{
	const MAX_CUE_POINTS_TO_COPY_TO_VOD = 100;
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::LIVE_TO_VOD;
	}
	/**
	 * (non-PHPdoc)
	 * @see KBatchBase::getJobType()
	 */
	protected function getJobType()
	{
		return BorhanBatchJobType::LIVE_TO_VOD;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->copyCuePoint($job, $job->data);
	}
	
	/**
	 * Will take a data and copy cue points
	 */
	private function copyCuePoint(BorhanBatchJob $job, BorhanLiveToVodJobData $data)
	{
		$amfArray = json_decode($data->amfArray);
		$currentSegmentStartTime = self::getSegmentStartTime($amfArray);
		$currentSegmentEndTime = self::getSegmentEndTime($amfArray, $data->lastSegmentDuration + $data->lastSegmentDrift);
		self::normalizeAMFTimes($amfArray, $data->totalVodDuration, $data->lastSegmentDuration);

		$totalCount = self::getCuePointCount($data->liveEntryId, $currentSegmentEndTime, $data->lastCuePointSyncTime);
		if ($totalCount == 0)
			return $this->closeJob($job, null, null, "No cue point to copy", BorhanBatchJobStatus::FINISHED);
		else
			BorhanLog::info("Total count of cue-point to copy: " .$totalCount);
		
		do
		{
			$copiedCuePointIds = array();
			$liveCuePointsToCopy = self::getCuePointListForEntry($data->liveEntryId, $currentSegmentEndTime, $data->lastCuePointSyncTime);
			if (count($liveCuePointsToCopy) == 0)
				break;

			//set the parnter ID for adding the new cue points in multi request
			KBatchBase::impersonate($liveCuePointsToCopy[0]->partnerId);
			KBatchBase::$kClient->startMultiRequest();
			foreach ($liveCuePointsToCopy as $liveCuePoint)
			{
				$copiedCuePointId = self::copyCuePointToVOD($liveCuePoint, $currentSegmentStartTime, $amfArray, $data->vodEntryId);
				if ($copiedCuePointId)
					$copiedCuePointIds[] = $copiedCuePointId;
				else
					BorhanLog::info("Not copying cue point [$liveCuePoint->id]");
			}
			$response = KBatchBase::$kClient->doMultiRequest();
			self::checkForErrorInMultiRequestResponse($response);
			KBatchBase::unimpersonate();
			
			//start post-process for all copied cue-point
			BorhanLog::info("Copied [".count($copiedCuePointIds)."] cue-points");
			self::postProcessCuePoints($copiedCuePointIds);

			//decrease the totalCount (as the number of cue point return from server)
			$totalCount -= count($liveCuePointsToCopy);
		} while ($totalCount);

		return $this->closeJob($job, null, null, "Copy all cue points finished", BorhanBatchJobStatus::FINISHED);
	}


	private static function checkForErrorInMultiRequestResponse($response)
	{
		foreach ($response as $item)
			if (KBatchBase::$kClient->isError($item))  //throwExceptionIfError
				BorhanLog::alert("Error in copy");
	}

	private static function postProcessCuePoints($copiedCuePointIds)
	{
		KBatchBase::$kClient->startMultiRequest();
		foreach ($copiedCuePointIds as $copiedLiveCuePointId)
			KBatchBase::$kClient->cuePoint->updateStatus($copiedLiveCuePointId, BorhanCuePointStatus::HANDLED);
		KBatchBase::$kClient->doMultiRequest();
	}

	private static function getCuePointFilter($entryId, $currentSegmentEndTime, $lastCuePointSyncTime = null)
	{
		$filter = new BorhanCuePointFilter();
		$filter->entryIdEqual = $entryId;
		$filter->statusIn = CuePointStatus::READY;
		$filter->cuePointTypeIn = 'codeCuePoint.Code,thumbCuePoint.Thumb,annotation.Annotation';
		$filter->createdAtLessThanOrEqual = $currentSegmentEndTime;
		if($lastCuePointSyncTime)
			$filter->createdAtGreaterThanOrEqual = $lastCuePointSyncTime;
		return $filter;
	}

	private static function getCuePointCount($entryId, $currentSegmentEndTime, $lastCuePointSyncTime)
	{
		$filter = self::getCuePointFilter($entryId, $currentSegmentEndTime, $lastCuePointSyncTime);
		return KBatchBase::$kClient->cuePoint->count($filter);
	}

	private static function getCuePointListForEntry($entryId, $currentSegmentEndTime, $lastCuePointSyncTime)
	{
		$filter = self::getCuePointFilter($entryId, $currentSegmentEndTime, $lastCuePointSyncTime);
		$pager = new BorhanFilterPager();
		$pager->pageSize = self::MAX_CUE_POINTS_TO_COPY_TO_VOD;
		$result = KBatchBase::$kClient->cuePoint->listAction($filter, $pager);
		return $result->objects;
	}

	private static function getSegmentStartTime($amfArray)
	{
		if (count($amfArray) == 0)
		{
			BorhanLog::warning("getSegmentStartTime got an empty AMFs array - returning 0 as segment start time");
			return 0;
		}
		return ($amfArray[0]->ts - $amfArray[0]->pts) / 1000;
	}

	private static function getSegmentEndTime($amfArray, $segmentDuration)
	{
		return ((self::getSegmentStartTime($amfArray) * 1000) + $segmentDuration) / 1000;
	}
	// change the PTS of every amf to be relative to the beginning of the recording, and not to the beginning of the segment
	private static function normalizeAMFTimes(&$amfArray, $totalVodDuration, $currentSegmentDuration)
	{
		foreach($amfArray as $key=>$amf)
			$amfArray[$key]->pts = $amfArray[$key]->pts  + $totalVodDuration - $currentSegmentDuration;
	}

	private static function getOffsetForTimestamp($timestamp, $amfArray)
	{
		$minDistanceAmf = self::getClosestAMF($timestamp, $amfArray);
		$ret = 0;
		if (is_null($minDistanceAmf))
			BorhanLog::debug('minDistanceAmf is null - returning 0');
		elseif ($minDistanceAmf->ts > $timestamp)
			$ret = $minDistanceAmf->pts - ($minDistanceAmf->ts - $timestamp);
		else
			$ret = $minDistanceAmf->pts + ($timestamp - $minDistanceAmf->ts);
		// make sure we don't get a negative time
		$ret = max($ret,0);
		BorhanLog::debug('AMFs array is:' . print_r($amfArray, true) . 'getOffsetForTimestamp returning ' . $ret);
		return $ret;
	}

	private static function getClosestAMF($timestamp, $amfArray)
	{
		$len = count($amfArray);
		$ret = null;
		if ($len == 1)
			$ret = $amfArray[0];
		else if ($timestamp >= $amfArray[$len-1]->ts)
			$ret = $amfArray[$len-1];
		else if ($timestamp <= $amfArray[0]->ts)
			$ret = $amfArray[0];
		else if ($len > 1)
		{
			$lo = 0;
			$hi = $len - 1;
			while ($hi - $lo > 1)
			{
				$mid = round(($lo + $hi) / 2);
				if ($amfArray[$mid]->ts <= $timestamp)
					$lo = $mid;
				else
					$hi = $mid;
			}
			if (abs($amfArray[$hi]->ts - $timestamp) > abs($amfArray[$lo]->ts - $timestamp))
				$ret = $amfArray[$lo];
			else
				$ret = $amfArray[$hi];
		}
		BorhanLog::debug('getClosestAMF returning ' . print_r($ret, true));
		return $ret;
	}


	private static function cuePointFactory($cuePointType) {
		switch($cuePointType) {
			case "codeCuePoint.Code":
				return new BorhanCodeCuePoint();
			case "thumbCuePoint.Thumb":
				return new BorhanThumbCuePoint();
			case "annotation.Annotation":
				return new BorhanAnnotation();
			default:
				return null;
		}
	}

	private static function createCuePointToUpdate($cuePointType, $startTime){
		$newCuePoint = self::cuePointFactory($cuePointType);
		if (!$newCuePoint)
			return null;
		$newCuePoint->startTime = $startTime;
		return $newCuePoint;

	}
	private static function copyCuePointToVOD($liveCuePoint, $currentSegmentStartTime, $amfArray, $vodEntryId)
	{
		$cuePointCreationTime = $liveCuePoint->createdAt * 1000;
		// if the cp was before the segment start time - move it to the beginning of the segment.
		$cuePointCreationTime = max($cuePointCreationTime, $currentSegmentStartTime * 1000);

		$startTimeForCuePoint = self::getOffsetForTimestamp($cuePointCreationTime, $amfArray);
		if (!is_null($startTimeForCuePoint)) {
			$VODCuePoint = KBatchBase::$kClient->cuePoint->cloneAction($liveCuePoint->id, $vodEntryId);
			if (KBatchBase::$kClient->isError($VODCuePoint))
				return null;

			$cuePointToUpdate = self::createCuePointToUpdate($liveCuePoint->cuePointType, $startTimeForCuePoint);
			if ($cuePointToUpdate) {
				$res = KBatchBase::$kClient->cuePoint->update($VODCuePoint->id, $cuePointToUpdate);
				if (KBatchBase::$kClient->isError($res))
					return null;
				else return $liveCuePoint->id;
			}
		}
		return null;
	}
}

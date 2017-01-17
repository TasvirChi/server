<?php
/**
 * @package Scheduler
 * @subpackage Extract-Media
 */

/**
 * Will extract the media info of a single file 
 *
 * @package Scheduler
 * @subpackage Extract-Media
 */
class KAsyncExtractMedia extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::EXTRACT_MEDIA;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->extract($job, $job->data);
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::getMaxJobsEachRun()
	 */
	protected function getMaxJobsEachRun()
	{
		return 1;
	}
	
	/**
	 * Will take a single BorhanBatchJob and extract the media info for the given file
	 */
	private function extract(BorhanBatchJob $job, BorhanExtractMediaJobData $data)
	{
		$srcFileSyncDescriptor = reset($data->srcFileSyncs);
		$mediaFile = null;
		if($srcFileSyncDescriptor)
			$mediaFile = trim($srcFileSyncDescriptor->fileSyncLocalPath);
		
		if(!$this->pollingFileExists($mediaFile))
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "Source file $mediaFile does not exist", BorhanBatchJobStatus::RETRY);
		
		if(!is_file($mediaFile))
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "Source file $mediaFile is not a file", BorhanBatchJobStatus::FAILED);
		
		$this->updateJob($job, "Extracting file media info on $mediaFile", BorhanBatchJobStatus::QUEUED);
		
		$mediaInfo = $this->extractMediaInfo($job, $mediaFile);
		
		if(is_null($mediaInfo))
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::EXTRACT_MEDIA_FAILED, "Failed to extract media info: $mediaFile", BorhanBatchJobStatus::RETRY);
		}
		
		if($data->calculateComplexity)
			$this->calculateMediaFileComplexity($mediaInfo, $mediaFile);
		
		$duration = $mediaInfo->containerDuration;
		if(!$duration)
			$duration = $mediaInfo->videoDuration;
		if(!$duration)
			$duration = $mediaInfo->audioDuration;
		
		if($data->extractId3Tags)
			$this->extractId3Tags($mediaFile, $data, $duration);
		
		BorhanLog::debug("flavorAssetId [$data->flavorAssetId]");
		$mediaInfo->flavorAssetId = $data->flavorAssetId;
		$mediaInfo = $this->getClient()->batch->addMediaInfo($mediaInfo);
		$data->mediaInfoId = $mediaInfo->id;
		
		$this->updateJob($job, "Saving media info id $mediaInfo->id", BorhanBatchJobStatus::PROCESSED, $data);
		$this->closeJob($job, null, null, null, BorhanBatchJobStatus::FINISHED);
		
		return $job;
	}
	
	/**
	 * extractMediaInfo extract the file info using mediainfo and parse the returned data
	 *  
	 * @param string $mediaFile file full path
	 * @return BorhanMediaInfo or null for failure
	 */
	private function extractMediaInfo($job, $mediaFile)
	{
		$mediaInfo = null;
		try
		{
			$mediaFile = realpath($mediaFile);
			
			$engine = KBaseMediaParser::getParser($job->jobSubType, $mediaFile, self::$taskConfig, $job);
			if($engine)
			{
				$mediaInfo = $engine->getMediaInfo();
			}
			else
			{
				$err = "No media info parser engine found for job sub type [$job->jobSubType]";
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, $err, BorhanBatchJobStatus::FAILED);
			}
		}
		catch(Exception $ex)
		{
			BorhanLog::err($ex->getMessage());
			$mediaInfo = null;
		}
		
		return $mediaInfo;
	}
	
	/*
	 * Calculate media file 'complexity'
	 */
	private function calculateMediaFileComplexity(&$mediaInfo, $mediaFile)
	{
		$complexityValue = null;
		
		if(isset(self::$taskConfig->params->localTempPath) && file_exists(self::$taskConfig->params->localTempPath))
		{
			$ffmpegBin = isset(self::$taskConfig->params->ffmpegCmd)? self::$taskConfig->params->ffmpegCmd: null;
			$ffprobeBin = isset(self::$taskConfig->params->ffprobeCmd)? self::$taskConfig->params->ffprobeCmd: null;
			$mediaInfoBin = isset(self::$taskConfig->params->mediaInfoCmd)? self::$taskConfig->params->mediaInfoCmd: null;
			$calcComplexity = new KMediaFileComplexity($ffmpegBin, $ffprobeBin, $mediaInfoBin);
			
			$baseOutputName = tempnam(self::$taskConfig->params->localTempPath, "/complexitySampled_".pathinfo($mediaFile, PATHINFO_FILENAME)).".mp4";
			$stat = $calcComplexity->EvaluateSampled($mediaFile, $mediaInfo, $baseOutputName);
			if(isset($stat->complexityValue))
			{
				BorhanLog::log("Complexity: value($stat->complexityValue)");
				if(isset($stat->y))
					BorhanLog::log("Complexity: y($stat->y)");
				
				$complexityValue = $stat->complexityValue;
			}
		}
		
		if($complexityValue)
			$mediaInfo->complexityValue = $complexityValue;
	}
	
	private function extractId3Tags($filePath, BorhanExtractMediaJobData $data, $duration)
	{
		try
		{
			$borhanId3TagParser = new KSyncPointsMediaInfoParser($filePath);
			$syncPointArray = $borhanId3TagParser->getStreamSyncPointData();
			
			$outputFileName = pathinfo($filePath, PATHINFO_FILENAME) . ".data";
			$localTempSyncPointsFilePath = self::$taskConfig->params->localTempPath . DIRECTORY_SEPARATOR . $outputFileName;
			$sharedTempSyncPointFilePath = self::$taskConfig->params->sharedTempPath . DIRECTORY_SEPARATOR . $outputFileName;
			
			file_put_contents($localTempSyncPointsFilePath, serialize($syncPointArray));
			
			$this->moveDataFile($data, $localTempSyncPointsFilePath, $sharedTempSyncPointFilePath);
		}
		catch(Exception $ex) 
		{
			$this->unimpersonate();
			BorhanLog::warning("Failed to extract id3tags data or duration data with error: " . print_r($ex));
		}
		
	}
	
	private function moveDataFile(BorhanExtractMediaJobData $data, $localTempSyncPointsFilePath, $sharedTempSyncPointFilePath)
	{
		BorhanLog::debug("moving file from [$localTempSyncPointsFilePath] to [$sharedTempSyncPointFilePath]");
		$fileSize = kFile::fileSize($localTempSyncPointsFilePath);
		
		kFile::moveFile($localTempSyncPointsFilePath, $sharedTempSyncPointFilePath, true);
		clearstatcache();
		
		$this->setFilePermissions($sharedTempSyncPointFilePath);
		if(!$this->checkFileExists($sharedTempSyncPointFilePath, $fileSize))
			BorhanLog::warning("Failed to move file to [$sharedTempSyncPointFilePath]");
		else
			$data->destDataFilePath = $sharedTempSyncPointFilePath;
	}
}


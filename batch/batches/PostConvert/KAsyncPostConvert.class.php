<?php
/**
 * @package Scheduler
 * @subpackage Post-Convert
 */

/**
 * Will convert a single flavor and store it in the file system.
 * The state machine of the job is as follows:
 * 	 	get the flavor
 * 		convert using the right method
 * 		save recovery file in case of crash
 * 		move the file to the archive
 * 		set the entry's new status and file details
 *
 *
 * @package Scheduler
 * @subpackage Post-Convert
 */
class KAsyncPostConvert extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::POSTCONVERT;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->postConvert($job, $job->data);
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::getMaxJobsEachRun()
	 */
	protected function getMaxJobsEachRun()
	{
		return 1;
	}

	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanPostConvertJobData $data
	 * @return BorhanBatchJob
	 */
	private function postConvert(BorhanBatchJob $job, BorhanPostConvertJobData $data)
	{
		if($data->flavorParamsOutputId)
			$data->flavorParamsOutput = KBatchBase::$kClient->flavorParamsOutput->get($data->flavorParamsOutputId);
		
		try
		{
			$srcFileSyncDescriptor = reset($data->srcFileSyncs);
			$mediaFile = null;
			if($srcFileSyncDescriptor)
				$mediaFile = trim($srcFileSyncDescriptor->fileSyncLocalPath);
			
			if(!$data->flavorParamsOutput || !$data->flavorParamsOutput->sourceRemoteStorageProfileId)
			{
				if(!$this->pollingFileExists($mediaFile))
					return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "Source file $mediaFile does not exist", BorhanBatchJobStatus::RETRY);
				
				if(!is_file($mediaFile))
					return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "Source file $mediaFile is not a file", BorhanBatchJobStatus::FAILED);
			}
			
			$this->updateJob($job,"Extracting file media info on $mediaFile", BorhanBatchJobStatus::QUEUED);
		}
		catch(Exception $ex)
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED);
		}
		
		$mediaInfo = null;
		try
		{
			$engine = KBaseMediaParser::getParser($job->jobSubType, realpath($mediaFile), KBatchBase::$taskConfig, $job);
			if($engine)
			{
				BorhanLog::info("Media info engine [" . get_class($engine) . "]");
				$mediaInfo = $engine->getMediaInfo();
			}
			else
			{
				$err = "Media info engine not found for job subtype [".$job->jobSubType."]";
				BorhanLog::info($err);
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, $err, BorhanBatchJobStatus::FAILED);
			}
		}
		catch(Exception $ex)
		{
			BorhanLog::err("Error: " . $ex->getMessage());
			$mediaInfo = null;
		}
		
		/* @var $mediaInfo BorhanMediaInfo */
		if(is_null($mediaInfo))
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::EXTRACT_MEDIA_FAILED, "Failed to extract media info: $mediaFile", BorhanBatchJobStatus::FAILED);

		/*
		 * Look for silent/black conversions. Curently checked only for Webex/ARF products
		 */
		$detectMsg = null;
		if(isset($data->flavorParamsOutput) && isset($data->flavorParamsOutput->operators)
		&& strstr($data->flavorParamsOutput->operators, "webexNbrplayer.WebexNbrplayer")!=false) {
			$rv = $this->checkForValidityOfWebexProduct($data, realpath($mediaFile), $mediaInfo, $detectMsg);
			if($rv==false){
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::BLACK_OR_SILENT_CONTENT, $detectMsg, BorhanBatchJobStatus::FAILED);
			}
		}


		try
		{
			$mediaInfo->flavorAssetId = $data->flavorAssetId;
			$createdMediaInfo = $this->getClient()->batch->addMediaInfo($mediaInfo);
			/* @var $createdMediaInfo BorhanMediaInfo */
			
			// must save the mediaInfoId before reporting that the task is finished
			$msg = "Saving media info id $createdMediaInfo->id";
			if(isset($detectMsg))
				$msg.= "($detectMsg)";
			$this->updateJob($job, $msg, BorhanBatchJobStatus::PROCESSED, $data);
			
			$data->thumbPath = null;
			if(!$data->createThumb)
				return $this->closeJob($job, null, null, "Media info id $createdMediaInfo->id saved", BorhanBatchJobStatus::FINISHED, $data);
			
			// creates a temp file path
			$rootPath = KBatchBase::$taskConfig->params->localTempPath;
			$this->createDir($rootPath);
				
			// creates the path
			$uniqid = uniqid('thumb_');
			$thumbPath = $rootPath . DIRECTORY_SEPARATOR . $uniqid;
			
			$videoDurationSec = floor($mediaInfo->videoDuration / 1000);
			$data->thumbOffset = max(0 ,min($data->thumbOffset, $videoDurationSec));
			
			if($mediaInfo->videoHeight)
				$data->thumbHeight = $mediaInfo->videoHeight;
			
			if($mediaInfo->videoBitRate)
				$data->thumbBitrate = $mediaInfo->videoBitRate;
					
			// generates the thumbnail
			$thumbMaker = new KFFMpegThumbnailMaker($mediaFile, $thumbPath, KBatchBase::$taskConfig->params->FFMpegCmd);
			$created = $thumbMaker->createThumnail($data->thumbOffset, $mediaInfo->videoWidth, $mediaInfo->videoHeight, null, null, $mediaInfo->videoDar);
			
			if(!$created || !file_exists($thumbPath))
			{
				$data->createThumb = false;
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::THUMBNAIL_NOT_CREATED, 'Thumbnail not created', BorhanBatchJobStatus::FINISHED, $data);
			}
			$data->thumbPath = $thumbPath;
			
			$job = $this->moveFile($job, $data);
			
			if($this->checkFileExists($job->data->thumbPath))
				return $this->closeJob($job, null, null, null, BorhanBatchJobStatus::FINISHED, $data);
			
			$data->createThumb = false;
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, 'File not moved correctly', BorhanBatchJobStatus::FINISHED, $data);
		}
		catch(Exception $ex)
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED);
		}
	}
	
	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanPostConvertJobData $data
	 * @return BorhanBatchJob
	 */
	private function moveFile(BorhanBatchJob $job, BorhanPostConvertJobData $data)
	{
		// creates a temp file path
		$rootPath = KBatchBase::$taskConfig->params->sharedTempPath;
		if(! is_dir($rootPath))
		{
			if(! file_exists($rootPath))
			{
				BorhanLog::info("Creating temp thumbnail directory [$rootPath]");
				mkdir($rootPath);
			}
			else
			{
				// already exists but not a directory
				$err = "Cannot create temp thumbnail directory [$rootPath] due to an error. Please fix and restart";
				throw new Exception($err, -1);
			}
		}
		
		$uniqid = uniqid('thumb_');
		$sharedFile = realpath($rootPath) . DIRECTORY_SEPARATOR . $uniqid;
		
		clearstatcache();
		$fileSize = kFile::fileSize($data->thumbPath);
		rename($data->thumbPath, $sharedFile);
		if(!file_exists($sharedFile) || kFile::fileSize($sharedFile) != $fileSize)
		{
			$err = 'moving file failed';
			throw new Exception($err, -1);
		}
		
		$this->setFilePermissions($sharedFile);
		$data->thumbPath = $sharedFile;
		$job->data = $data;
		return $job;
	}
	
	/**
	 * Check for invalidly generated content files -
	 * - Silent or black content for at least 50% of the total duration
	 * - The detection duration - at least 2 sec
	 * - Applicable only to Webex sources
	 * @param BorhanBatchJob $job
	 * @param BorhanPostConvertJobData $data
	 * $param $mediaFile
	 * #param BorhanMediaInfo $mediaInfo
	 * @return boolean
	 */
	private function checkForValidityOfWebexProduct(BorhanPostConvertJobData $data, $srcFileName, BorhanMediaInfo $mediaInfo, &$detectMsg)
	{
		$rv = true;
		$detectMsg = null;
		/*
		 * Get silent and black portions
		 *
		list($silenceDetect, $blackDetect) = KFFMpegMediaParser::checkForSilentAudioAndBlackVideo(KBatchBase::$taskConfig->params->FFMpegCmd, $srcFileName, $mediaInfo);
		
		$detectMsg = $silenceDetect;
		if(isset($blackDetect))
			$detectMsg = isset($detectMsg)?"$detectMsg,$blackDetect":$blackDetect;
		*/
		/*
		 * Silent/Black does not cause validation failure, just a job message 
		 */
		if(isset($detectMsg)){
//			return false;
		}
		
		/*
		 * Get number of Webex operators that represent the number of conversion retries.
		 * Return success after the last retry, independently of whether the result is garbled or not.
		 * The assumption is that 3 retries will bring the number of garbled audios to acceptable rate.
		 * Therefore if the audio is still garbled, it is probably due to false detection,
		 * therefore DO NOT fail the asset.
		 */
		$operators = json_decode($data->flavorParamsOutput->operators);
		if($data->currentOperationSet<count($operators)-1) {
			if(KFFMpegMediaParser::checkForGarbledAudio(KBatchBase::$taskConfig->params->FFMpegCmd, $srcFileName, $mediaInfo)==true) {
				$detectMsg.= " Garbled Audio!";
				$rv = false;
			}
		}
		
		return $rv;
	}
}

<?php
/**
 * @package Scheduler
 * @subpackage Conversion
 */

/**
 * Will convert a collection of flavors and store it in the file system.
 * The state machine of the job is as follows:
 * 	 	get the flavors
 * 		convert using the right method
 * 		save recovery file in case of crash
 * 		move the file to the archive
 * 		set the entry's new status and file details
 *
 * @package Scheduler
 * @subpackage Conversion
 */
class KAsyncConvertCollection extends KAsyncConvert
{
	/**
	 * @return number
	 */
	public static function getType()
	{
		return BorhanBatchJobType::CONVERT_COLLECTION;
	}
	
	protected function convertImpl(BorhanBatchJob $job, BorhanConvartableJobData $data)
	{
		return $this->convertCollection($job, $data);
	}
	
	private function filesExist($relativePath, array $files)
	{
		if(!count($files))
			return false;
	
		$filesExist = true;
		foreach($files as $file)
		{
			if(!file_exists($file))
				$filesExist = false;
		}
		return $filesExist;
	}
	
	private function convertCollection(BorhanBatchJob $job, BorhanConvertCollectionJobData $data)
	{
		foreach ($data->srcFileSyncs as $srcFileSyncDescriptor) 
		{				
			if(self::$taskConfig->params->isRemoteInput || !strlen(trim($srcFileSyncDescriptor->actualFileSyncLocalPath))) // for distributed conversion
			{
				if(!strlen(trim($srcFileSyncDescriptor->actualFileSyncLocalPath)))
					$srcFileSyncDescriptor->actualFileSyncLocalPath = self::$taskConfig->params->localFileRoot . DIRECTORY_SEPARATOR . basename($srcFileSyncDescriptor->fileSyncRemoteUrl);
					
				$err = null;
				if(!$this->distributedFileManager->getLocalPath($srcFileSyncDescriptor->actualFileSyncLocalPath, $srcFileSyncDescriptor->fileSyncRemoteUrl, $err))
				{
					return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::REMOTE_FILE_NOT_FOUND, $err, BorhanBatchJobStatus::RETRY);
				}
			}
			
			if(file_exists($srcFileSyncDescriptor->actualFileSyncLocalPath))
			{
				BorhanLog::info("Source file exists [$srcFileSyncDescriptor->actualFileSyncLocalPath]");
			}
			else
			{
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "Source file not found [$srcFileSyncDescriptor->actualFileSyncLocalPath]", BorhanBatchJobStatus::RETRY);
			}
			
			if(!is_file($srcFileSyncDescriptor->actualFileSyncLocalPath))
			{
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "Source file [$srcFileSyncDescriptor->actualFileSyncLocalPath] is not a file", BorhanBatchJobStatus::FAILED);
			}
		}
		
		$data->destDirLocalPath = $this->localTempPath;
		$data->inputXmlLocalPath = $this->translateSharedPath2Local($data->inputXmlLocalPath);
	
		if(self::$taskConfig->params->isRemoteInput || !strlen(trim($data->inputXmlLocalPath))) // for distributed conversion
		{
			if(!strlen(trim($data->inputXmlLocalPath)))
				$data->inputXmlLocalPath = self::$taskConfig->params->localFileRoot . DIRECTORY_SEPARATOR . basename($data->inputXmlLocalPath);
				
			$err = null;
			if(!$this->distributedFileManager->getLocalPath($data->inputXmlLocalPath, $data->inputXmlRemoteUrl, $err))
			{
				return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::REMOTE_FILE_NOT_FOUND, $err, BorhanBatchJobStatus::RETRY);
			}
		}

		
		if(file_exists($data->inputXmlLocalPath))
		{
			BorhanLog::info("XML Configuration file exists [$data->inputXmlLocalPath]");
		}
		else
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, null, "XML Configuration file not found [$data->inputXmlLocalPath]", BorhanBatchJobStatus::RETRY);
		}
		
		if(!is_file($data->inputXmlLocalPath))
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, "XML Configuration file [$data->inputXmlLocalPath] is not a file", BorhanBatchJobStatus::FAILED);
		}
		
		$logFilePath = $data->destDirLocalPath . DIRECTORY_SEPARATOR . $data->destFileName . '.log';
		$monitorFiles = array(
			$logFilePath
		);
		$this->startMonitor($monitorFiles);
	
		$operator = $this->getOperator($data);
		BorhanLog::debug("getOperator(".print_r($data,true).") => operator(".print_r($operator,true).")");
		$log = null;
		try
		{
			$actualFileSyncLocalPath = null;
			$srcFileSyncDescriptor = reset($data->srcFileSyncs);			
			if($srcFileSyncDescriptor)
				$actualFileSyncLocalPath = $srcFileSyncDescriptor->actualFileSyncLocalPath;
			//TODO: in future remove the inFilePath parameter from operate method, the input files passed to operation
			//engine as part of the data
			$this->operationEngine->operate($operator, $actualFileSyncLocalPath, $data->destFileSyncLocalPath);
		}
		catch(KOperationEngineException $e)
		{
			$log = $this->operationEngine->getLogData();
			
			$err = "engine [" . get_class($this->operationEngine) . "] convert failed: " . $e->getMessage();
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::CONVERSION_FAILED, $err, BorhanBatchJobStatus::FAILED);
		}
		$this->stopMonitor();
	
		$videoEntities = $this->operationEngine->getOutFilesPath();
		foreach($videoEntities as $bitrate => $flavorPath)
		{
			foreach($data->flavors as $index => $flavor)
				if($flavor->videoBitrate == $bitrate)
					$data->flavors[$index]->destFileSyncLocalPath = $flavorPath;
		}
		BorhanLog::debug ( "Flavors data: " . print_r($data->flavors, true));
			
		$job = $this->updateJob($job, "engine [" . get_class($this->operationEngine) . "] convert successfully", BorhanBatchJobStatus::MOVEFILE, $data);
		return $this->moveFiles($job, $job->data);
	}
	
	private function moveFiles(BorhanBatchJob $job, BorhanConvertCollectionJobData $data)
	{
		clearstatcache();
		$files2move = array();
		
		$fileNames = array(
			$data->destFileName . '.log',
			$data->destFileName . '.ism',
			$data->destFileName . '.ismc',
			$data->destFileName . '_Thumb.jpg'
		);
		
		foreach($fileNames as $fileName)
		{
			$srcPath = $data->destDirLocalPath . DIRECTORY_SEPARATOR . $fileName;
			if(!file_exists($srcPath))
				continue;
				
			$destPath = $this->sharedTempPath . DIRECTORY_SEPARATOR . $fileName;
			$sharedPath = $this->translateLocalPath2Shared($destPath);
			$fileSize = kFile::fileSize($srcPath);
			
			BorhanLog::debug("add to move list file[$srcPath] to[$destPath] size[$fileSize] shared path[$sharedPath]");
			$files2move[] = array(
				'from' => $srcPath,
				'to' => $destPath,
				'path' => $sharedPath,
				'size' => $fileSize,
			);
		}
		
		foreach($data->flavors as $flavor)
		{
			$srcPath = $flavor->destFileSyncLocalPath;
			if(!file_exists($srcPath))
				continue;
				
			$destPath = $this->sharedTempPath . DIRECTORY_SEPARATOR . basename($srcPath);
			$sharedPath = $this->translateLocalPath2Shared($destPath);
			$fileSize = kFile::fileSize($srcPath);
			
			$flavor->destFileSyncLocalPath = $sharedPath;
			if(self::$taskConfig->params->isRemoteOutput)
				$flavor->destFileSyncRemoteUrl = $this->distributedFileManager->getRemoteUrl($sharedPath);
						
			BorhanLog::debug("add to move list file[$srcPath] to[$destPath] size[$fileSize] shared path[$sharedPath]");
			$files2move[] = array(
				'from' => $srcPath,
				'to' => $destPath,
				'path' => $sharedPath,
				'size' => $fileSize,
			);
		}
		
		foreach($files2move as $file2move)
		{
			$srcPath = $file2move['from'];
			$destPath = $file2move['to'];
			$fileSize = $file2move['size'];
			
			BorhanLog::debug("moving file[$srcPath] to[$destPath] size[$fileSize]");
			
			if(file_exists($destPath))
			{
				BorhanLog::debug("delete existing file[$destPath]");
				unlink($destPath);
			}
				
			BorhanLog::debug("rename($srcPath, $destPath)");
			rename($srcPath, $destPath);
		
			if(!file_exists($destPath) || kFile::fileSize($destPath) != $fileSize)
			{
				BorhanLog::err("Error: moving file [$srcPath] failed");
				die();
			}
			$this->setFilePermissions($destPath);
		}
		
		$data->destDirLocalPath = $this->translateLocalPath2Shared($this->sharedTempPath);
		if(self::$taskConfig->params->isRemoteOutput) // for remote conversion
		{
			$data->destDirRemoteUrl = $this->distributedFileManager->getRemoteUrl($data->destDirLocalPath);
			$job->status = BorhanBatchJobStatus::ALMOST_DONE;
			$job->message = "Files ready for download";
		}
		elseif($this->checkFilesArrayExist($files2move))
		{
			$job->status = BorhanBatchJobStatus::FINISHED;
			$job->message = "Files moved to shared";
		}
		else
		{
			$job->status = BorhanBatchJobStatus::RETRY;
			$job->message = "Files not moved correctly";
		}
		return $this->closeJob($job, null, null, $job->message, $job->status, $data);
	}
	
	protected function updateExclusiveJob($jobId, BorhanBatchJob $job)
	{
		$flavors = null;
		if((isset($job->data->flavors)))
		{
			$flavors = $job->data->flavors;
			$job->data->flavors = null;
		}
		return self::$kClient->batch->updateExclusiveConvertCollectionJob($jobId, $this->getExclusiveLockKey(), $job, $flavors);
	}
}

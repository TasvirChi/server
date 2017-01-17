<?php
/**
 * 
 */
class KWebexDropFolderEngine extends KDropFolderEngine 
{
	const ZERO_DATE = '12/31/1971 00:00:01';
	
	const ARF_FORMAT = 'ARF';

	private static $unsupported_file_formats = array('WARF');
	
	/**
	 * Webex XML API client
	 * @var WebexXmlClient
	 */
	protected $webexClient;
	
	public function watchFolder (BorhanDropFolder $dropFolder)
	{
		/* @var $dropFolder BorhanWebexDropFolder */
		$this->dropFolder = $dropFolder;
		$this->webexClient = $this->initWebexClient();
		BorhanLog::info('Watching folder ['.$this->dropFolder->id.']');
		
		$startTime = null;
		$endTime = null;
		if ($this->dropFolder->incremental)
		{
			$startTime = ($this->dropFolder->lastFileTimestamp ? date('m/j/Y H:i:s', $this->dropFolder->lastFileTimestamp - 3600) :  self::ZERO_DATE);
			$endTime = (date('m/j/Y H:i:s', time()+86400));
		}
		$physicalFiles = $this->listRecordings($startTime, $endTime);
		BorhanLog::info('Recordings fetched: '.print_r($physicalFiles, true) );
		
		if (!count($physicalFiles))
		{
			BorhanLog::info('No new files to handle at this time');			
			return;
		}
		
		$dropFolderFilesMap = $this->loadDropFolderFiles();
		$maxTime = $this->dropFolder->lastFileTimestamp;
		foreach ($physicalFiles as $physicalFile)
		{
			/* @var $physicalFile WebexXmlEpRecordingType */
			if (in_array($physicalFile->getFormat(),self::$unsupported_file_formats))
			{
				BorhanLog::info('Recording with id [' . $physicalFile->getRecordingID() . '] format [' . $physicalFile->getFormat() . '] is incompatible with the Borhan conversion processes. Ignoring.');
				continue;
			}
			
			$physicalFileName = $physicalFile->getName() . '_' . $physicalFile->getRecordingID();
			if(!array_key_exists($physicalFileName, $dropFolderFilesMap))
			{
				$this->handleFileAdded ($physicalFile);
				$maxTime = max(strtotime($physicalFile->getCreateTime()), $maxTime);
				BorhanLog::info("maxTime updated: $maxTime");
			}
		}
		
		if ($this->dropFolder->incremental && $maxTime > $this->dropFolder->lastFileTimestamp)
		{
			$updateDropFolder = new BorhanDropFolder();
			$updateDropFolder->lastFileTimestamp = $maxTime;
			$this->dropFolderPlugin->dropFolder->update($this->dropFolder->id, $updateDropFolder);
		}
		
		if ($this->dropFolder->fileDeletePolicy != BorhanDropFolderFileDeletePolicy::MANUAL_DELETE)
		{
			$this->purgeFiles ($dropFolderFilesMap);
		}
		
	}
	
	public function processFolder (BorhanBatchJob $job, BorhanDropFolderContentProcessorJobData $data)
	{
		KBatchBase::impersonate ($job->partnerId);
		
		/* @var $data BorhanWebexDropFolderContentProcessorJobData */
		$dropFolder = $this->dropFolderPlugin->dropFolder->get ($data->dropFolderId);
		//In the case of the webex drop folder engine, the only possible contentMatch policy is ADD_AS_NEW.
		//Any other policy should cause an error.
		switch ($data->contentMatchPolicy)
		{
			case BorhanDropFolderContentFileHandlerMatchPolicy::ADD_AS_NEW:
				$this->addAsNewContent($job, $data, $dropFolder);
				break;
			default:
				throw new kApplicativeException(BorhanDropFolderErrorCode::DROP_FOLDER_APP_ERROR, 'Content match policy not allowed for Webex drop folders');
				break;
		}
		
		KBatchBase::unimpersonate();
	}
	
	protected function listRecordings ($startTime = null, $endTime = null)
	{
		BorhanLog::info("Fetching list of recordings from Webex, startTime [$startTime], endTime [$endTime]");
		$fileList = array();
		$startFrom = 1;
		try{
			
			do
			{
				$listControl = new WebexXmlEpListControlType();
				$listControl->setStartFrom($startFrom);
				$listRecordingRequest = new WebexXmlListRecordingRequest();
				$listRecordingRequest->setListControl($listControl);
				
				$servicesTypes = new WebexXmlArray('WebexXmlComServiceTypeType');
				$servicesTypes[] = new WebexXmlComServiceTypeType(WebexXmlComServiceTypeType::_MEETINGCENTER);
				$listRecordingRequest->setServiceTypes($servicesTypes);
	 			
				if($startTime && $endTime)
				{
					$createTimeScope = new WebexXmlEpCreateTimeScopeType();
					$createTimeScope->setCreateTimeStart($startTime);
					$createTimeScope->setCreateTimeEnd($endTime);
					$listRecordingRequest->setCreateTimeScope($createTimeScope);
				}
				
				
				$listRecordingResponse = $this->webexClient->send($listRecordingRequest);
				
				$fileList = array_merge($fileList, $listRecordingResponse->getRecording());
				$startFrom = $listRecordingResponse->getMatchingRecords()->getStartFrom() + $listRecordingResponse->getMatchingRecords()->getReturned();
			}while (count ($fileList) < $listRecordingResponse->getMatchingRecords()->getTotal());
		}
		catch (Exception $e)
		{
			BorhanLog::err("Error occured: " . print_r($e, true));
			if ($e->getCode() != 15 && $e->getMessage() != 'Status: FAILURE, Reason: Sorry, no record found')
			{
				throw $e;
			}
		}
		
		
		return $fileList;
	}
	
	protected function initWebexClient ()
	{
		$securityContext = new WebexXmlSecurityContext();
		$securityContext->setUid($this->dropFolder->webexUserId); // webex username
		$securityContext->setPwd($this->dropFolder->webexPassword); // webex password
		$securityContext->setSid($this->dropFolder->webexSiteId); // webex site id
		$securityContext->setPid($this->dropFolder->webexPartnerId); // webex partner id
		return new WebexXmlClient($this->dropFolder->webexServiceUrl . '/' . $this->dropFolder->path, $securityContext);
	}
	
	/**
	 * 
	 * @param array $dropFolderFilesMap
	 * @throws Exception
	 */
	protected function purgeFiles ($dropFolderFilesMap)
	{
		$createTimeEnd = strtotime ("now") - ($this->dropFolder->autoFileDeleteDays*86400);
		$fileList = $this->listRecordings(self::ZERO_DATE, date('m/j/Y H:i:s',$createTimeEnd));
		BorhanLog::info("Files to delete: " . count($fileList));
		
		foreach ($fileList as $file)
		{
			$physicalFileName = $file->getName() . '_' . $file->getRecordingID();
			if (!array_key_exists($physicalFileName, $dropFolderFilesMap))
			{
				BorhanLog::info("File with name $physicalFileName not handled yet. Ignoring");
				continue;
			}
			
			$dropFolderFile = $dropFolderFilesMap[$physicalFileName];
			/* @var $dropFolderFile BorhanWebexDropFolderFile */
			if (!in_array($dropFolderFile->status, array(BorhanDropFolderFileStatus::HANDLED, BorhanDropFolderFileStatus::DELETED)))
			{
				BorhanLog::info("File with name $physicalFileName not in final status. Ignoring");
				continue;
			}
			
			/* @var $file WebexXmlEpRecordingType */
			$deleteRecordingRequest = new WebexXmlDelRecordingRequest();
			$deleteRecordingRequest->setRecordingID($file->getRecordingID());
			$deleteRecordingRequest->setIsServiceRecording(1);
			
			try {
				$response = $this->webexClient->send($deleteRecordingRequest);
				BorhanLog::info("File [$physicalFileName] successfully purged. Purging drop folder file");
				$this->dropFolderFileService->updateStatus($dropFolderFile->id, BorhanDropFolderFileStatus::PURGED);
			}
			catch (Exception $e)
			{
				BorhanLog::err('Error occured: ' . print_r($e, true));
			}
		}
	}
	
	
	protected function handleFileAdded (WebexXmlEpRecordingType $webexFile)
	{
		try 
		{
			$newDropFolderFile = new BorhanWebexDropFolderFile();
	    	$newDropFolderFile->dropFolderId = $this->dropFolder->id;
	    	$newDropFolderFile->fileName = $webexFile->getName() . '_' . $webexFile->getRecordingID();
	    	$newDropFolderFile->fileSize = $webexFile->getSize() * 1024*1024;
	    	$newDropFolderFile->lastModificationTime = $webexFile->getCreateTime(); 
			$newDropFolderFile->description = $webexFile->getDescription();
			$newDropFolderFile->confId = $webexFile->getConfID();
			$newDropFolderFile->recordingId = $webexFile->getRecordingID();
			$newDropFolderFile->webexHostId = $webexFile->getHostWebExID();
			$newDropFolderFile->contentUrl = $webexFile->getFileURL();
			BorhanLog::debug('content url '. $newDropFolderFile->contentUrl . ' file url: ' .$webexFile->getFileURL() );
			//No such thing as an 'uploading' webex drop folder file - if the file is detected, it is ready for upload. Immediately update status to 'pending'
			KBatchBase::$kClient->startMultiRequest();
			$dropFolderFile = $this->dropFolderFileService->add($newDropFolderFile);
			$this->dropFolderFileService->updateStatus($dropFolderFile->id, BorhanDropFolderFileStatus::PENDING);
			$result = KBatchBase::$kClient->doMultiRequest();
			
			return $result[1];
		}
		catch(Exception $e)
		{
			BorhanLog::err('Cannot add new drop folder file with name ['.$webexFile->getName() . '_' . $webexFile->getRecordingID().'] - '.$e->getMessage());
			return null;
		}
	}

	protected function addAsNewContent (BorhanBatchJob $job, BorhanWebexDropFolderContentProcessorJobData $data, BorhanWebexDropFolder $folder)
	{
		/* @var $data BorhanWebexDropFolderContentProcessorJobData */
		$resource = $this->getIngestionResource($job, $data);
		$newEntry = new BorhanMediaEntry();
		$newEntry->mediaType = BorhanMediaType::VIDEO;
		$newEntry->conversionProfileId = $data->conversionProfileId;
		$newEntry->name = $data->parsedSlug;
		$newEntry->description = $data->description;
		$newEntry->userId = $data->parsedUserId ? $data->parsedUserId : $this->retrieveUserFromWebexHostId($data, $folder);
		$newEntry->creatorId = $newEntry->userId;
		$newEntry->referenceId = $data->parsedSlug;
			
		KBatchBase::$kClient->startMultiRequest();
		$addedEntry = KBatchBase::$kClient->media->add($newEntry, null);
		KBatchBase::$kClient->baseEntry->addContent($addedEntry->id, $resource);
		$result = KBatchBase::$kClient->doMultiRequest();
		
		if ($result [1] && $result[1] instanceof BorhanBaseEntry)
		{
			$entry = $result [1];
			$this->createCategoryAssociations ($folder, $entry->userId, $entry->id);
		}
	}

	
	protected function retrieveUserFromWebexHostId (BorhanWebexDropFolderContentProcessorJobData $data, BorhanWebexDropFolder $folder)
	{
		if ($folder->metadataProfileId && $folder->webexHostIdMetadataFieldName && $data->webexHostId)
		{
			$filter = new BorhanUserFilter();
			$filter->advancedSearch = new BorhanMetadataSearchItem();
			$filter->advancedSearch->metadataProfileId = $folder->metadataProfileId;
			$webexHostIdSearchCondition = new BorhanSearchCondition();
			$webexHostIdSearchCondition->field = $folder->webexHostIdMetadataFieldName;
			$webexHostIdSearchCondition->value = $data->webexHostId;
			$filter->advancedSearch->items = array($webexHostIdSearchCondition);
			try
			{
				$result = KBatchBase::$kClient->user->listAction ($filter, new BorhanFilterPager());
				
				if ($result->totalCount)
				{
					$user = $result->objects[0];
					return $user->id;
				}
			}
			catch (Exception $e)
			{
				BorhanLog::err('Error encountered. Code: ['. $e->getCode() . '] Message: [' . $e->getMessage() . ']');
			}

		}
		
		return $data->webexHostId;
	}
	
}

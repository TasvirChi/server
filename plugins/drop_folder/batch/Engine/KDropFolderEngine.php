<?php
/**
 * 
 */
abstract class KDropFolderEngine implements IBorhanLogger
{
	protected $dropFolder;
	
	protected $dropFolderPlugin;
	
	protected $dropFolderFileService;
	
	public function __construct ()
	{
		$this->dropFolderPlugin = BorhanDropFolderClientPlugin::get(KBatchBase::$kClient);
		$this->dropFolderFileService = $this->dropFolderPlugin->dropFolderFile;
	}
	
	public static function getInstance ($dropFolderType)
	{
		switch ($dropFolderType) {
			case BorhanDropFolderType::FTP:
			case BorhanDropFolderType::SFTP:
			case BorhanDropFolderType::LOCAL:
				return new KDropFolderFileTransferEngine ();
				break;
			
			default:
				return BorhanPluginManager::loadObject('KDropFolderEngine', $dropFolderType);
				break;
		}
	}
	
	abstract public function watchFolder (BorhanDropFolder $dropFolder);
	
	abstract public function processFolder (BorhanBatchJob $job, BorhanDropFolderContentProcessorJobData $data);
	
	/**
	 * Load all the files from the database that their status is not PURGED, PARSED or DETECTED
	 * @param BorhanDropFolder $folder
	 */
	protected function loadDropFolderFiles()
	{
		$dropFolderFilesMap = array();
		$dropFolderFiles =null;
		
		$dropFolderFileFilter = new BorhanDropFolderFileFilter();
		$dropFolderFileFilter->dropFolderIdEqual = $this->dropFolder->id;
		$dropFolderFileFilter->statusNotIn = BorhanDropFolderFileStatus::PARSED.','.BorhanDropFolderFileStatus::DETECTED;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 500;
		if(KBatchBase::$taskConfig->params->pageSize)
			$pager->pageSize = KBatchBase::$taskConfig->params->pageSize;	

		do
		{
			$pager->pageIndex++;
			$dropFolderFiles = $this->dropFolderFileService->listAction($dropFolderFileFilter, $pager);
			$dropFolderFiles = $dropFolderFiles->objects;
			foreach ($dropFolderFiles as $dropFolderFile) 
			{
				$dropFolderFilesMap[$dropFolderFile->fileName] = $dropFolderFile;
			}
		}while (count($dropFolderFiles) >= $pager->pageSize);
			
		return $dropFolderFilesMap;
	}

	/**
 	 * Update drop folder entity with error
	 * @param int $dropFolderFileId
	 * @param int $errorStatus
	 * @param int $errorCode
	 * @param string $errorMessage
	 * @param Exception $e
	 */
	protected function handleFileError($dropFolderFileId, $errorStatus, $errorCode, $errorMessage, Exception $e = null)
	{
		try 
		{
			if($e)
				BorhanLog::err('Error for drop folder file with id ['.$dropFolderFileId.'] - '.$e->getMessage());
			else
				BorhanLog::err('Error for drop folder file with id ['.$dropFolderFileId.'] - '.$errorMessage);
			
			$updateDropFolderFile = new BorhanDropFolderFile();
			$updateDropFolderFile->errorCode = $errorCode;
			$updateDropFolderFile->errorDescription = $errorMessage;
			$this->dropFolderFileService->update($dropFolderFileId, $updateDropFolderFile);
			return $this->dropFolderFileService->updateStatus($dropFolderFileId, $errorStatus);				
		}
		catch (BorhanException $e) 
		{
			BorhanLog::err('Cannot set error details for drop folder file id ['.$dropFolderFileId.'] - '.$e->getMessage());
			return null;
		}
	}
	
	/**
	 * Mark file status as PURGED
	 * @param int $dropFolderFileId
	 */
	protected function handleFilePurged($dropFolderFileId)
	{
		try 
		{
			return $this->dropFolderFileService->updateStatus($dropFolderFileId, BorhanDropFolderFileStatus::PURGED);
		}
		catch(Exception $e)
		{
			$this->handleFileError($dropFolderFileId, BorhanDropFolderFileStatus::ERROR_HANDLING, BorhanDropFolderFileErrorCode::ERROR_UPDATE_FILE, 
									DropFolderPlugin::ERROR_UPDATE_FILE_MESSAGE, $e);
			
			return null;
		}		
	}
	
	/**
	 * Retrieve all the relevant drop folder files according to the list of id's passed on the job data.
	 * Create resource object based on the conversion profile as an input to the ingestion API
	 * @param BorhanBatchJob $job
	 * @param BorhanDropFolderContentProcessorJobData $data
	 */
	protected function getIngestionResource(BorhanBatchJob $job, BorhanDropFolderContentProcessorJobData $data)
	{
		$filter = new BorhanDropFolderFileFilter();
		$filter->idIn = $data->dropFolderFileIds;
		$dropFolderFiles = $this->dropFolderFileService->listAction($filter); 
		
		$resource = null;
		if($dropFolderFiles->totalCount == 1 && is_null($dropFolderFiles->objects[0]->parsedFlavor)) //only source is ingested
		{
			$resource = new BorhanDropFolderFileResource();
			$resource->dropFolderFileId = $dropFolderFiles->objects[0]->id;			
		}
		else //ingest all the required flavors
		{			
			$fileToFlavorMap = array();
			foreach ($dropFolderFiles->objects as $dropFolderFile) 
			{
				$fileToFlavorMap[$dropFolderFile->parsedFlavor] = $dropFolderFile->id;			
			}
			
			$assetContainerArray = array();
		
			$assetParamsFilter = new BorhanConversionProfileAssetParamsFilter();
			$assetParamsFilter->conversionProfileIdEqual = $data->conversionProfileId;
			$assetParamsList = KBatchBase::$kClient->conversionProfileAssetParams->listAction($assetParamsFilter);
			foreach ($assetParamsList->objects as $assetParams)
			{
				if(array_key_exists($assetParams->systemName, $fileToFlavorMap))
				{
					$assetContainer = new BorhanAssetParamsResourceContainer();
					$assetContainer->assetParamsId = $assetParams->assetParamsId;
					$assetContainer->resource = new BorhanDropFolderFileResource();
					$assetContainer->resource->dropFolderFileId = $fileToFlavorMap[$assetParams->systemName];
					$assetContainerArray[] = $assetContainer;				
				}			
			}		
			$resource = new BorhanAssetsParamsResourceContainers();
			$resource->resources = $assetContainerArray;
		}
		return $resource;		
	}

	protected function createCategoryAssociations (BorhanDropFolder $folder, $userId, $entryId)
	{
		if ($folder->metadataProfileId && $folder->categoriesMetadataFieldName)
		{
			$filter = new BorhanMetadataFilter();
			$filter->metadataProfileIdEqual = $folder->metadataProfileId;
			$filter->objectIdEqual = $userId;
			$filter->metadataObjectTypeEqual = BorhanMetadataObjectType::USER;
			
			try
			{
				$metadataPlugin = BorhanMetadataClientPlugin::get(KBatchBase::$kClient);
				//Expect only one result
				$res = $metadataPlugin->metadata->listAction($filter, new BorhanFilterPager());
				$metadataObj = $res->objects[0];
				$xmlElem = new SimpleXMLElement($metadataObj->xml);
				$categoriesXPathRes = $xmlElem->xpath($folder->categoriesMetadataFieldName);
				$categories = array();
				foreach ($categoriesXPathRes as $catXPath)
				{
					$categories[] = strval($catXPath);
				}
				
				$categoryFilter = new BorhanCategoryFilter();
				$categoryFilter->idIn = implode(',', $categories);
				$categoryListResponse = KBatchBase::$kClient->category->listAction ($categoryFilter, new BorhanFilterPager());
				if ($categoryListResponse->objects && count($categoryListResponse->objects))
				{
					if (!$folder->enforceEntitlement)
					{
						//easy
						$this->createCategoryEntriesNoEntitlement ($categoryListResponse->objects, $entryId);
					}
					else {
						//write your will
						$this->createCategoryEntriesWithEntitlement ($categoryListResponse->objects, $entryId, $userId);
					}
				}
			}
			catch (Exception $e)
			{
				BorhanLog::err('Error encountered. Code: ['. $e->getCode() . '] Message: [' . $e->getMessage() . ']');
			}
		}
	}

	private function createCategoryEntriesNoEntitlement (array $categoriesArr, $entryId)
	{
		KBatchBase::$kClient->startMultiRequest();
		foreach ($categoriesArr as $category)
		{
			$categoryEntry = new BorhanCategoryEntry();
			$categoryEntry->entryId = $entryId;
			$categoryEntry->categoryId = $category->id;
			KBatchBase::$kClient->categoryEntry->add($categoryEntry);
		}
		KBatchBase::$kClient->doMultiRequest();
	}
	
	private function createCategoryEntriesWithEntitlement (array $categoriesArr, $entryId, $userId)
	{
		$partnerInfo = KBatchBase::$kClient->partner->get(KBatchBase::$kClientConfig->partnerId);
		
		$clientConfig = new BorhanConfiguration($partnerInfo->id);
		$clientConfig->serviceUrl = KBatchBase::$kClient->getConfig()->serviceUrl;
		$clientConfig->setLogger($this);
		$client = new BorhanClient($clientConfig);
		foreach ($categoriesArr as $category)
		{
			/* @var $category BorhanCategory */
			$ks = $client->generateSessionV2($partnerInfo->adminSecret, $userId, BorhanSessionType::ADMIN, $partnerInfo->id, 86400, 'enableentitlement,privacycontext:'.$category->privacyContexts);
			$client->setKs($ks);
			$categoryEntry = new BorhanCategoryEntry();
			$categoryEntry->categoryId = $category->id;
			$categoryEntry->entryId = $entryId;
			try
			{
				$client->categoryEntry->add ($categoryEntry);
			}
			catch (Exception $e)
			{
				BorhanLog::err("Could not add entry $entryId to category {$category->id}. Exception thrown.");
			}
		}
	}
	
	function log($message)
	{
		BorhanLog::log($message);
	}
}

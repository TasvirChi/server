<?php
/**
 * @package Scheduler
 * @subpackage Bulk-Upload
 */
class DropFolderXmlBulkUploadEngine extends BulkUploadEngineXml
{
	/**
	 * @var BorhanDropFolder
	 */
	private $dropFolder = null;
	
	/**
	 * @var BorhanDropFolderFile
	 */
	private $xmlDropFolderFile = null;
	
	/**
	 * @var kFileTransferMgr
	 */
	private $fileTransferMgr = null;
	
	/**
	 *
	 * @var array
	 */
	private $contentResourceNameToIdMap = null;
	
	/**
	 * XML provided KS info
	 * @var BorhanSessionInfo
	 */
	private $ksInfo = null;
	
	public function __construct(BorhanBatchJob $job)
	{
		parent::__construct($job);
		
		KBatchBase::impersonate($this->currentPartnerId);
		$dropFolderPlugin = BorhanDropFolderClientPlugin::get(KBatchBase::$kClient);
		KBatchBase::$kClient->startMultiRequest();
		$dropFolderFile = $dropFolderPlugin->dropFolderFile->get($this->job->jobObjectId);
		$dropFolderPlugin->dropFolder->get($dropFolderFile->dropFolderId);
		list($this->xmlDropFolderFile, $this->dropFolder) = KBatchBase::$kClient->doMultiRequest();
				
		$this->fileTransferMgr = KDropFolderFileTransferEngine::getFileTransferManager($this->dropFolder);
		$this->data->filePath = $this->getLocalFilePath($this->xmlDropFolderFile->fileName, $this->xmlDropFolderFile->id);
		
		KBatchBase::unimpersonate();
	}
	
	/* (non-PHPdoc)
	 * @see BulkUploadEngineXml::getSchemaType()
	 */
	protected function getSchemaType()
	{
		return BorhanSchemaType::DROP_FOLDER_XML;
	}
	
	/* (non-PHPdoc)
	 * @see BulkUploadEngineXml::handleBulkUpload()
	 */
	public function handleBulkUpload()
	{
		KBatchBase::impersonate($this->currentPartnerId);
		$dropFolderPlugin = BorhanDropFolderClientPlugin::get(KBatchBase::$kClient);
		$this->setContentResourceFilesMap($dropFolderPlugin);
		KBatchBase::unimpersonate();
		
		parent::handleBulkUpload();
	}
	
	private function setContentResourceFilesMap(BorhanDropFolderClientPlugin $dropFolderPlugin)
	{
		$filter = new BorhanDropFolderFileFilter();
		$filter->dropFolderIdEqual = $this->dropFolder->id;
		$filter->leadDropFolderFileIdEqual = $this->xmlDropFolderFile->id;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 500;
		$pager->pageIndex = 1;
		
		$getNextPage = true;
		
		$this->contentResourceNameToIdMap = array();
		
		while($getNextPage)
		{
			$dropFolderFiles = $dropFolderPlugin->dropFolderFile->listAction($filter, $pager);
			foreach ($dropFolderFiles->objects as $dropFolderFile)
			{
				$this->contentResourceNameToIdMap[$dropFolderFile->fileName] = $dropFolderFile->id;
			}
			
			if(count($dropFolderFiles->objects) < $pager->pageSize)
				$getNextPage = false;
			else
				$pager->pageIndex++;
		}
	}
	
	/* (non-PHPdoc)
	 * @see BulkUploadEngineXml::getResourceInstance()
	 */
	protected function getResourceInstance(SimpleXMLElement $elementToSearchIn, $conversionProfileId)
	{
		if(isset($elementToSearchIn->dropFolderFileContentResource))
		{
			$resource = new BorhanDropFolderFileResource();
			$attributes = $elementToSearchIn->dropFolderFileContentResource->attributes();
			$filePath = (string)$attributes['filePath'];
			$resource->dropFolderFileId = $this->contentResourceNameToIdMap[$filePath];
			
			return $resource;
		}
		
		return parent::getResourceInstance($elementToSearchIn, $conversionProfileId);
	}
	
	/* (non-PHPdoc)
	 * @see BulkUploadEngineXml::validateResource()
	 */
	protected function validateResource(BorhanResource $resource, SimpleXMLElement $elementToSearchIn)
	{
		if($resource instanceof BorhanDropFolderFileResource)
		{
			$fileId = $resource->dropFolderFileId;
			if (is_null($fileId)) {
				throw new BorhanBulkUploadXmlException("Drop folder id is null", BorhanBatchJobAppErrors::BULK_ITEM_VALIDATION_FAILED);
			}
						
			$filePath = $this->getFilePath($elementToSearchIn);
			$this->validateFileSize($elementToSearchIn, $filePath);
			if($this->dropFolder->type == BorhanDropFolderType::LOCAL)
			{
				$this->validateChecksum($elementToSearchIn, $filePath);
			}
		}
		
		return parent::validateResource($resource, $elementToSearchIn);
	}
	
	private function getFilePath(SimpleXMLElement $elementToSearchIn)
	{
		$attributes = $elementToSearchIn->dropFolderFileContentResource->attributes();
		$filePath = (string)$attributes['filePath'];
		
		if(isset($filePath))
		{
			$filePath = $this->dropFolder->path.'/'.$filePath;
			if($this->dropFolder->type == BorhanDropFolderType::LOCAL)
				$filePath = realpath($filePath);
			return $filePath;
		}
		else
		{
			throw new BorhanBulkUploadXmlException("Can't validate file as file path is null", BorhanBatchJobAppErrors::BULK_ITEM_VALIDATION_FAILED);
		}
	}
	
	private function validateFileSize(SimpleXMLElement $elementToSearchIn, $filePath)
	{
		if(isset($elementToSearchIn->dropFolderFileContentResource->fileSize))
		{
			$fileSize = $this->fileTransferMgr->fileSize($filePath);
			$xmlFileSize = (int)$elementToSearchIn->dropFolderFileContentResource->fileSize;
			if($xmlFileSize != $fileSize)
				throw new BorhanBulkUploadXmlException("File size is invalid for file [$filePath], Xml size [$xmlFileSize], actual size [$fileSize]", BorhanBatchJobAppErrors::BULK_ITEM_VALIDATION_FAILED);
		}
	}
	
	private function validateChecksum(SimpleXMLElement $elementToSearchIn, $filePath)
	{
		if(isset($elementToSearchIn->dropFolderFileContentResource->fileChecksum))
		{
			if($elementToSearchIn->dropFolderFileContentResource->fileChecksum['type'] == 'sha1')
			{
				 $checksum = sha1_file($filePath);
			}
			else
			{
				$checksum = md5_file($filePath);
			}
			
			$xmlChecksum = (string)$elementToSearchIn->dropFolderFileContentResource->fileChecksum;
			if($xmlChecksum != $checksum)
			{
				throw new BorhanBulkUploadXmlException("File checksum is invalid for file [$filePath], Xml checksum [$xmlChecksum], actual checksum [$checksum]", BorhanBatchJobAppErrors::BULK_ITEM_VALIDATION_FAILED);
			}
			BorhanLog::info("Checksum [$checksum] verified for local resource [$filePath]");
		}
	}
	
	/**
	 * Local drop folder - constract full path
	 * Remote drop folder - download file to a local temp directory and return the temp file path
	 * @param string $fileName
	 * @param int $fileId
	 * @throws Exception
	 */
	protected function getLocalFilePath($fileName, $fileId)
	{
		$dropFolderFilePath = $this->dropFolder->path.'/'.$fileName;
	    
	    // local drop folder
	    if ($this->dropFolder->type == BorhanDropFolderType::LOCAL) 
	    {
	        $dropFolderFilePath = realpath($dropFolderFilePath);
	        return $dropFolderFilePath;
	    }
	    else
	    {
	    	// remote drop folder	
			$tempFilePath = tempnam(KBatchBase::$taskConfig->params->sharedTempPath, 'parse_dropFolderFileId_'.$fileId.'_');		
			$this->fileTransferMgr->getFile($dropFolderFilePath, $tempFilePath);
			$this->setFilePermissions ($tempFilePath);
			return $tempFilePath;
	    }			    		
	}
	
	protected function setFilePermissions ($filepath)
	{
		$chmod = 0640;
		if(KBatchBase::$taskConfig->getChmod())
			$chmod = octdec(KBatchBase::$taskConfig->getChmod());
			
		BorhanLog::info("chmod($filepath, $chmod)");
		@chmod($filepath, $chmod);
		
		$chown_name = KBatchBase::$taskConfig->params->fileOwner;
		if ($chown_name) {
			BorhanLog::info("Changing owner of file [$filepath] to [$chown_name]");
			@chown($filepath, $chown_name);
		}
	}
	
	protected function validate()
	{
		$isValid = parent::validate();
		
		if($this->dropFolder->shouldValidateKS){
			$this->validateKs();		
		}
		
		return $isValid;
	}
	
	protected function validateKs()
	{
		//Retrieve the KS from within the XML
		$xdoc = new SimpleXMLElement($this->xslTransformedContent);
		$xmlKs = $xdoc->ks;
		
		//Get session info
		KBatchBase::impersonate($this->currentPartnerId);
		try{
			$this->ksInfo = KBatchBase::$kClient->session->get($xmlKs);	
		}
		catch (Exception $e){
			KBatchBase::unimpersonate();
			throw new BorhanBatchException("KS [$xmlKs] validation failed for [{$this->job->id}], $errorMessage", BorhanBatchJobAppErrors::BULK_VALIDATION_FAILED);
		}
		KBatchBase::unimpersonate();
		
		//validate ks is still valid
		$currentTime = time();
		if($currentTime > $this->ksInfo->expiry){
			throw new BorhanBatchException("KS validation failed for [{$this->job->id}], ks provided in XML Expired", BorhanBatchJobAppErrors::BULK_VALIDATION_FAILED);
		}
	}
	
	/**
	 * Validates the given item's user id is identical to the user id on the KS
	 * @param SimpleXMLElement $item
	 */
	protected function validateItem(SimpleXMLElement $item)
	{
		if($this->dropFolder->shouldValidateKS){
			if(!isset($item->userId) && $this->ksInfo->sessionType == BorhanSessionType::USER)
				throw new BorhanBulkUploadXmlException("Drop Folder is set with KS validation but no user id was provided", BorhanBatchJobAppErrors::BULK_ITEM_VALIDATION_FAILED);
			if($item->userId != $this->ksInfo->userId && $this->ksInfo->sessionType == BorhanSessionType::USER)
				throw new BorhanBulkUploadXmlException("Drop Folder is set with KS validation, KS user ID [" . $this->ksInfo->userId . "] does not match item user ID [" . $item->userId . "]", BorhanBatchJobAppErrors::BULK_ITEM_VALIDATION_FAILED);
		}
			
		parent::validateItem($item);
	}
	
	protected function createEntryFromItem(SimpleXMLElement $item, $type = null)
	{
		$entry = parent::createEntryFromItem($item, $type);
		
		if($this->dropFolder->shouldValidateKS && !isset($entry->userId))
			$entry->userId = $this->ksInfo->userId;
			
		return $entry;
	}
}
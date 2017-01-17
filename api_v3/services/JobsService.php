<?php
/**
 * batch service lets you handle different batch process from remote machines.
 * As opposed to other objects in the system, locking mechanism is critical in this case.
 * For this reason the GetExclusiveXX, UpdateExclusiveXX and FreeExclusiveXX actions are important for the system's intergity.
 * In general - updating batch object should be done only using the UpdateExclusiveXX which in turn can be called only after 
 * acuiring a batch objet properly (using  GetExclusiveXX).
 * If an object was aquired and should be returned to the pool in it's initial state - use the FreeExclusiveXX action 
 *
 *	Terminology:
 *		LocationId
 *		ServerID
 *		ParternGroups 
 * 
 * @service jobs
 * @package api
 * @subpackage services
 */
class JobsService extends BorhanBaseService 
{
	// use initService to add a peer to the partner filter
	/**
	 * @ignore
	 */
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		if($this->getPartnerId() != Partner::ADMIN_CONSOLE_PARTNER_ID)
			$this->applyPartnerFilterForClass('BatchJob'); 	
	}
	
	
// --------------------------------- ImportJob functions 	--------------------------------- //
	
	
	/**
	 * batch getImportStatusAction returns the status of import task
	 * 
	 * @action getImportStatus
	 * @param int $jobId the id of the import job  
	 * @return BorhanBatchJobResponse 
	 */
	function getImportStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::IMPORT);
	}
	
	
	/**
	 * batch deleteImportAction deletes and returns the status of import task
	 * 
	 * @action deleteImport
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteImportAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::IMPORT);
	}
	
	
	/**
	 * batch abortImportAction aborts and returns the status of import task
	 * 
	 * @action abortImport
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortImportAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::IMPORT);
	}
	
	
	/**
	 * batch retryImportAction retrys and returns the status of import task
	 * 
	 * @action retryImport
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryImportAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::IMPORT);
	}
	
	/**
// --------------------------------- ImportJob functions 	--------------------------------- //

	
	
	
// --------------------------------- ProvisionProvideJob functions 	--------------------------------- //
	
	
	/**
	 * batch getProvisionProvideStatusAction returns the status of ProvisionProvide task
	 * 
	 * @action getProvisionProvideStatus
	 * @param int $jobId the id of the ProvisionProvide job  
	 * @return BorhanBatchJobResponse 
	 */
	function getProvisionProvideStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::PROVISION_PROVIDE);
	}
	
	
	/**
	 * batch deleteProvisionProvideAction deletes and returns the status of ProvisionProvide task
	 * 
	 * @action deleteProvisionProvide
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteProvisionProvideAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::PROVISION_PROVIDE);
	}
	
	
	/**
	 * batch abortProvisionProvideAction aborts and returns the status of ProvisionProvide task
	 * 
	 * @action abortProvisionProvide
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortProvisionProvideAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::PROVISION_PROVIDE);
	}
	
	
	/**
	 * batch retryProvisionProvideAction retrys and returns the status of ProvisionProvide task
	 * 
	 * @action retryProvisionProvide
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryProvisionProvideAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::PROVISION_PROVIDE);
	}
	
	/**
// --------------------------------- ProvisionProvideJob functions 	--------------------------------- //

	
	
// --------------------------------- ProvisionDeleteJob functions 	--------------------------------- //
	
	
	/**
	 * batch getProvisionDeleteStatusAction returns the status of ProvisionDelete task
	 * 
	 * @action getProvisionDeleteStatus
	 * @param int $jobId the id of the ProvisionDelete job  
	 * @return BorhanBatchJobResponse 
	 */
	function getProvisionDeleteStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::PROVISION_DELETE);
	}
	
	
	/**
	 * batch deleteProvisionDeleteAction deletes and returns the status of ProvisionDelete task
	 * 
	 * @action deleteProvisionDelete
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteProvisionDeleteAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::PROVISION_DELETE);
	}
	
	
	/**
	 * batch abortProvisionDeleteAction aborts and returns the status of ProvisionDelete task
	 * 
	 * @action abortProvisionDelete
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortProvisionDeleteAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::PROVISION_DELETE);
	}
	
	
	/**
	 * batch retryProvisionDeleteAction retrys and returns the status of ProvisionDelete task
	 * 
	 * @action retryProvisionDelete
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryProvisionDeleteAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::PROVISION_DELETE);
	}
	
	/**
// --------------------------------- ProvisionDeleteJob functions 	--------------------------------- //

	
	
// --------------------------------- BulkUploadJob functions 	--------------------------------- //
	
	
	/**
	 * batch getBulkUploadStatusAction returns the status of bulk upload task
	 * 
	 * @action getBulkUploadStatus
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function getBulkUploadStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::BULKUPLOAD);
	}
	
	
	/**
	 * batch deleteBulkUploadAction deletes and returns the status of bulk upload task
	 * 
	 * @action deleteBulkUpload
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteBulkUploadAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::BULKUPLOAD);
	}
	
	
	/**
	 * batch abortBulkUploadAction aborts and returns the status of bulk upload task
	 * 
	 * @action abortBulkUpload
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortBulkUploadAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::BULKUPLOAD);
	}
	
	
	/**
	 * batch retryBulkUploadAction retrys and returns the status of bulk upload task
	 * 
	 * @action retryBulkUpload
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryBulkUploadAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::BULKUPLOAD);
	}
	

	
// --------------------------------- BulkUploadJob functions 	--------------------------------- //

	
	
// --------------------------------- ConvertJob functions 	--------------------------------- //

	
	
	/**
	 * batch getConvertStatusAction returns the status of convert task
	 * 
	 * @action getConvertStatus
	 * @param int $jobId the id of the convert job  
	 * @return BorhanBatchJobResponse 
	 */
	function getConvertStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::CONVERT);
	}
	
	
	
	/**
	 * batch getConvertCollectionStatusAction returns the status of convert task
	 * 
	 * @action getConvertCollectionStatus
	 * @param int $jobId the id of the convert profile job  
	 * @return BorhanBatchJobResponse 
	 */
	function getConvertCollectionStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::CONVERT_COLLECTION);
	}
	
	
	
	/**
	 * batch getConvertProfileStatusAction returns the status of convert task
	 * 
	 * @action getConvertProfileStatus
	 * @param int $jobId the id of the convert profile job  
	 * @return BorhanBatchJobResponse 
	 */
	function getConvertProfileStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::CONVERT_PROFILE);
	}
	
	
	
	/**
	 * batch addConvertProfileJobAction creates a new convert profile job
	 * 
	 * @action addConvertProfileJob
	 * @param string $entryId the id of the entry to be reconverted  
	 * @return BorhanBatchJobResponse 
	 */
	function addConvertProfileJobAction($entryId)
	{
		$entry = entryPeer::retrieveByPK($entryId);
		if(!$entry)
			throw new BorhanAPIException(APIErrors::INVALID_ENTRY_ID, 'entry', $entryId);
			
		$flavorAsset = assetPeer::retrieveOriginalByEntryId($entryId);
		if(!$flavorAsset)
			throw new BorhanAPIException(BorhanErrors::ORIGINAL_FLAVOR_ASSET_IS_MISSING);
		
		$syncKey = $flavorAsset->getSyncKey(flavorAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
		if(!kFileSyncUtils::file_exists($syncKey, true))
			throw new BorhanAPIException(APIErrors::NO_FILES_RECEIVED);
			
		$inputFileSyncLocalPath = kFileSyncUtils::getLocalFilePathForKey($syncKey);
		
		$batchJob = kJobsManager::addConvertProfileJob(null, $entry, $flavorAsset->getId(), $inputFileSyncLocalPath);
		if(!$batchJob)
			throw new BorhanAPIException(APIErrors::UNABLE_TO_CONVERT_ENTRY);
		
		return $this->getStatusAction($batchJob->getId(), BorhanBatchJobType::CONVERT_PROFILE);
	}
	
	
	/**
	 * batch deleteConvertAction deletes and returns the status of convert task
	 * 
	 * @action deleteConvert
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteConvertAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::CONVERT);
	}

	
	/**
	 * batch abortConvertAction aborts and returns the status of convert task
	 * 
	 * @action abortConvert
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortConvertAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::CONVERT);
	}

	
	/**
	 * batch retryConvertAction retrys and returns the status of convert task
	 * 
	 * @action retryConvert
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryConvertAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::CONVERT);
	}

	
	/**
	 * batch deleteConvertCollectionAction deletes and returns the status of convert profile task
	 * 
	 * @action deleteConvertCollection
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteConvertCollectionAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::CONVERT_COLLECTION);
	}

	
	/**
	 * batch deleteConvertProfileAction deletes and returns the status of convert profile task
	 * 
	 * @action deleteConvertProfile
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteConvertProfileAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::CONVERT_PROFILE);
	}

	
	/**
	 * batch abortConvertCollectionAction aborts and returns the status of convert profile task
	 * 
	 * @action abortConvertCollection
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortConvertCollectionAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::CONVERT_COLLECTION);
	}

	
	/**
	 * batch abortConvertProfileAction aborts and returns the status of convert profile task
	 * 
	 * @action abortConvertProfile
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortConvertProfileAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::CONVERT_PROFILE);
	}

	
	/**
	 * batch retryConvertCollectionAction retrys and returns the status of convert profile task
	 * 
	 * @action retryConvertCollection
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryConvertCollectionAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::CONVERT_COLLECTION);
	}

	
	/**
	 * batch retryConvertProfileAction retrys and returns the status of convert profile task
	 * 
	 * @action retryConvertProfile
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryConvertProfileAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::CONVERT_PROFILE);
	}
	
// --------------------------------- ConvertJob functions 	--------------------------------- //

	
	
// --------------------------------- PostConvertJob functions 	--------------------------------- //

	
	/**
	 * batch getPostConvertStatusAction returns the status of post convert task
	 * 
	 * @action getPostConvertStatus
	 * @param int $jobId the id of the post convert job  
	 * @return BorhanBatchJobResponse 
	 */
	function getPostConvertStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::POSTCONVERT);
	}
	
	
	/**
	 * batch deletePostConvertAction deletes and returns the status of post convert task
	 * 
	 * @action deletePostConvert
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deletePostConvertAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::POSTCONVERT);
	}
	
	
	/**
	 * batch abortPostConvertAction aborts and returns the status of post convert task
	 * 
	 * @action abortPostConvert
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortPostConvertAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::POSTCONVERT);
	}
	
	
	/**
	 * batch retryPostConvertAction retrys and returns the status of post convert task
	 * 
	 * @action retryPostConvert
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryPostConvertAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::POSTCONVERT);
	}
	

// --------------------------------- PostConvertJob functions 	--------------------------------- //

// --------------------------------- CaptureThumbJob functions 	--------------------------------- //

	
	/**
	 * batch getCaptureThumbStatusAction returns the status of capture thumbnail task
	 * 
	 * @action getCaptureThumbStatus
	 * @param int $jobId the id of the capture thumbnail job  
	 * @return BorhanBatchJobResponse 
	 */
	function getCaptureThumbStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::CAPTURE_THUMB);
	}
	
	
	/**
	 * batch deleteCaptureThumbAction deletes and returns the status of capture thumbnail task
	 * 
	 * @action deleteCaptureThumb
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteCaptureThumbAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::CAPTURE_THUMB);
	}
	
	
	/**
	 * batch abortCaptureThumbAction aborts and returns the status of capture thumbnail task
	 * 
	 * @action abortCaptureThumb
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortCaptureThumbAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::CAPTURE_THUMB);
	}
	
	
	/**
	 * batch retryCaptureThumbAction retrys and returns the status of capture thumbnail task
	 * 
	 * @action retryCaptureThumb
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryCaptureThumbAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::CAPTURE_THUMB);
	}
	

// --------------------------------- CaptureThumbJob functions 	--------------------------------- //
	
	
// --------------------------------- ExtractMediaJob functions 	--------------------------------- //
	
	
	/**
	 * batch getExtractMediaStatusAction returns the status of extract media task
	 * 
	 * @action getExtractMediaStatus
	 * @param int $jobId the id of the extract media job  
	 * @return BorhanBatchJobResponse 
	 */
	function getExtractMediaStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::EXTRACT_MEDIA);
	}
	
	
	/**
	 * batch deleteExtractMediaAction deletes and returns the status of extract media task
	 * 
	 * @action deleteExtractMedia
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteExtractMediaAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::EXTRACT_MEDIA);
	}
	
	
	/**
	 * batch abortExtractMediaAction aborts and returns the status of extract media task
	 * 
	 * @action abortExtractMedia
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortExtractMediaAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::EXTRACT_MEDIA);
	}
	
	
	/**
	 * batch retryExtractMediaAction retrys and returns the status of extract media task
	 * 
	 * @action retryExtractMedia
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryExtractMediaAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::EXTRACT_MEDIA);
	}
	

	
	
// --------------------------------- ExtractMediaJob functions 	--------------------------------- //
	
// --------------------------------- StorageExportJob functions 	--------------------------------- //
	
	
	/**
	 * batch getStorageExportStatusAction returns the status of export task
	 * 
	 * @action getStorageExportStatus
	 * @param int $jobId the id of the export job  
	 * @return BorhanBatchJobResponse 
	 */
	function getStorageExportStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::STORAGE_EXPORT);
	}
	
	
	/**
	 * batch deleteStorageExportAction deletes and returns the status of export task
	 * 
	 * @action deleteStorageExport
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteStorageExportAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::STORAGE_EXPORT);
	}
	
	
	/**
	 * batch abortStorageExportAction aborts and returns the status of export task
	 * 
	 * @action abortStorageExport
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortStorageExportAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::STORAGE_EXPORT);
	}
	
	
	/**
	 * batch retryStorageExportAction retrys and returns the status of export task
	 * 
	 * @action retryStorageExport
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryStorageExportAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::STORAGE_EXPORT);
	}
	

	
	
// --------------------------------- StorageExportJob functions 	--------------------------------- //
	
// --------------------------------- StorageDeleteJob functions 	--------------------------------- //
	
	
	/**
	 * batch getStorageDeleteStatusAction returns the status of export task
	 * 
	 * @action getStorageDeleteStatus
	 * @param int $jobId the id of the export job  
	 * @return BorhanBatchJobResponse 
	 */
	function getStorageDeleteStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::STORAGE_DELETE);
	}
	
	
	/**
	 * batch deleteStorageDeleteAction deletes and returns the status of export task
	 * 
	 * @action deleteStorageDelete
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteStorageDeleteAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::STORAGE_DELETE);
	}
	
	
	/**
	 * batch abortStorageDeleteAction aborts and returns the status of export task
	 * 
	 * @action abortStorageDelete
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortStorageDeleteAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::STORAGE_DELETE);
	}
	
	
	/**
	 * batch retryStorageDeleteAction retrys and returns the status of export task
	 * 
	 * @action retryStorageDelete
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryStorageDeleteAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::STORAGE_DELETE);
	}
	

	
	
// --------------------------------- StorageDeleteJob functions 	--------------------------------- //
	
// --------------------------------- ImportJob functions 	--------------------------------- //
	
	/**
	 * batch getNotificationStatusAction returns the status of Notification task
	 * 
	 * @action getNotificationStatus
	 * @param int $jobId the id of the Notification job  
	 * @return BorhanBatchJobResponse 
	 */
	function getNotificationStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::NOTIFICATION);
	}
	
	
	/**
	 * batch deleteNotificationAction deletes and returns the status of notification task
	 * 
	 * @action deleteNotification
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteNotificationAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::NOTIFICATION);
	}
	
	
	/**
	 * batch abortNotificationAction aborts and returns the status of notification task
	 * 
	 * @action abortNotification
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortNotificationAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::NOTIFICATION);
	}
	
	
	/**
	 * batch retryNotificationAction retrys and returns the status of notification task
	 * 
	 * @action retryNotification
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryNotificationAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::NOTIFICATION);
	}
	
	
// --------------------------------- Notification functions 	--------------------------------- //


	
// --------------------------------- MailJob functions 	--------------------------------- //	
	
	
	/**
	 * batch getMailStatusAction returns the status of mail task
	 * 
	 * @action getMailStatus
	 * @param int $jobId the id of the mail job  
	 * @return BorhanBatchJobResponse 
	 */
	function getMailStatusAction($jobId)
	{
		return $this->getStatusAction($jobId, BorhanBatchJobType::MAIL);
	}
	
	
	/**
	 * batch deleteMailAction deletes and returns the status of mail task
	 * 
	 * @action deleteMail
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteMailAction($jobId)
	{
		return $this->deleteJobAction($jobId, BorhanBatchJobType::MAIL);
	}
	
	
	/**
	 * batch abortMailAction aborts and returns the status of mail task
	 * 
	 * @action abortMail
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortMailAction($jobId)
	{
		return $this->abortJobAction($jobId, BorhanBatchJobType::MAIL);
	}
	
	
	/**
	 * batch retryMailAction retrys and returns the status of mail task
	 * 
	 * @action retryMail
	 * @param int $jobId the id of the bulk upload job  
	 * @return BorhanBatchJobResponse 
	 */
	function retryMailAction($jobId)
	{
		return $this->retryJobAction($jobId, BorhanBatchJobType::MAIL);
	}
	
	/**
	 * Adds new mail job
	 * 
	 * @action addMailJob
	 * @param BorhanMailJobData $mailJobData
	 */
	function addMailJobAction(BorhanMailJobData $mailJobData)
	{
		$mailJobData->validatePropertyNotNull("mailType");
		$mailJobData->validatePropertyNotNull("recipientEmail");
		
		if (is_null($mailJobData->mailPriority))
			$mailJobData->mailPriority = kMailJobData::MAIL_PRIORITY_NORMAL;
			
		if (is_null($mailJobData->fromEmail))
			$mailJobData->fromEmail = kConf::get("default_email");

		if (is_null($mailJobData->fromName))
			$mailJobData->fromName = kConf::get("default_email_name");
			
		$batchJob = new BatchJob();
		$batchJob->setPartnerId($this->getPartnerId());
		
		$mailJobDataDb = $mailJobData->toObject(new kMailJobData());
			
		kJobsManager::addJob($batchJob, $mailJobDataDb, BatchJobType::MAIL, $mailJobDataDb->getMailType());
	}
	
// --------------------------------- MailJob functions 	--------------------------------- //
	
		
// --------------------------------- generic functions 	--------------------------------- //
	
	
	/**
	 * batch addBatchJob action allows to add a generic BatchJob 
	 * 
	 * @action addBatchJob
	 * @param BorhanBatchJob $batchJob  
	 * @return BorhanBatchJob 
	 */
	function addBatchJobAction(BorhanBatchJob $batchJob)
	{
		kJobsManager::addJob($batchJob->toObject(), $batchJob->data, $batchJob->jobType, $batchJob->jobSubType);	
	}

	
	
	/**
	 * batch getStatusAction returns the status of task
	 * 
	 * @action getStatus
	 * @param int $jobId the id of the job  
	 * @param BorhanBatchJobType $jobType the type of the job
	 * @param BorhanFilterPager $pager pager for the child jobs  
	 * @return BorhanBatchJobResponse 
	 */
	function getStatusAction($jobId, $jobType, BorhanFilterPager $pager = null)
	{
		$dbJobType = kPluginableEnumsManager::apiToCore('BatchJobType', $jobType);
		
		$dbBatchJob = BatchJobPeer::retrieveByPK($jobId);
		if($dbBatchJob->getJobType() != $dbJobType)
			throw new BorhanAPIException(APIErrors::GET_EXCLUSIVE_JOB_WRONG_TYPE, $jobType, $dbBatchJob->getId());
		
		$dbBatchJobLock = BatchJobLockPeer::retrieveByPK($jobId);
		
		$job = new BorhanBatchJob();
		$job->fromBatchJob($dbBatchJob,$dbBatchJobLock);
		
		$batchJobResponse = new BorhanBatchJobResponse();
		$batchJobResponse->batchJob = $job;
		
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		$c = new Criteria();
		$pager->attachToCriteria($c);
		
		$childBatchJobs = $dbBatchJob->getChildJobs($c);
		$batchJobResponse->childBatchJobs = BorhanBatchJobArray::fromBatchJobArray($childBatchJobs);
		
		return $batchJobResponse;
	}

	
	
	/**
	 * batch deleteJobAction deletes and returns the status of task
	 * 
	 * @action deleteJob
	 * @param int $jobId the id of the job  
	 * @param BorhanBatchJobType $jobType the type of the job  
	 * @return BorhanBatchJobResponse 
	 */
	function deleteJobAction($jobId, $jobType)
	{
		$dbJobType = kPluginableEnumsManager::apiToCore('BatchJobType', $jobType);
		kJobsManager::deleteJob($jobId, $dbJobType);
		return $this->getStatusAction($jobId, $jobType);
	}

	
	
	/**
	 * batch abortJobAction aborts and returns the status of task
	 * 
	 * @action abortJob
	 * @param int $jobId the id of the job  
	 * @param BorhanBatchJobType $jobType the type of the job  
	 * @return BorhanBatchJobResponse 
	 */
	function abortJobAction($jobId, $jobType)
	{
		$dbJobType = kPluginableEnumsManager::apiToCore('BatchJobType', $jobType);
		kJobsManager::abortJob($jobId, $dbJobType);
		return $this->getStatusAction($jobId, $jobType);
	}

	
	
	/**
	 * batch retryJobAction aborts and returns the status of task
	 * 
	 * @action retryJob
	 * @param int $jobId the id of the job  
	 * @param BorhanBatchJobType $jobType the type of the job  
	 * @param bool $force should we force the restart. 
	 * @return BorhanBatchJobResponse 
	 */
	function retryJobAction($jobId, $jobType, $force = false)
	{
		$dbJobType = kPluginableEnumsManager::apiToCore('BatchJobType', $jobType);
		kJobsManager::retryJob($jobId, $dbJobType, $force);
		return $this->getStatusAction($jobId, $jobType);
	}
	
	/**
	 * batch boostEntryJobsAction boosts all the jobs associated with the entry
	 * 
	 * @action boostEntryJobs
	 * @param string $entryId the id of the entry to be boosted  
	 */
	function boostEntryJobsAction($entryId)
	{
		kJobsManager::boostEntryJobs($entryId);
	}

	/**
	 * list Batch Jobs 
	 * 
	 * @action listBatchJobs
	 * @param BorhanBatchJobFilter $filter
	 * @param BorhanFilterPager $pager  
	 * @return BorhanBatchJobListResponse
	 */
	function listBatchJobsAction(BorhanBatchJobFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter) 
			$filter = new BorhanBatchJobFilter();
			
		$batchJobFilter = new BatchJobFilter (true);
		$filter->toObject($batchJobFilter);
		
		$c = new Criteria();
//		$c->add(BatchJobPeer::DELETED_AT, null);
		
		$batchJobFilter->attachToCriteria($c);
		
		if(!$pager)
		   $pager = new BorhanFilterPager();
		
		$pager->attachToCriteria($c);
		
		myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL2;
		
		$list = BatchJobPeer::doSelect($c);
		
		$c->setLimit(false);
		$count = BatchJobPeer::doCount($c);

		$newList = BorhanBatchJobArray::fromStatisticsBatchJobArray($list );
		
		$response = new BorhanBatchJobListResponse();
		$response->objects = $newList;
		$response->totalCount = $count;
		
		return $response;
	}
	
// --------------------------------- generic functions 	--------------------------------- //	
	
	
	
}

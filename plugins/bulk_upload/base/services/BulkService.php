<?php
/**
 * Bulk upload service is used to upload & manage bulk uploads
 *
 * @service bulk
 * @package plugins.bulkUpload
 * @subpackage services
 */
class BulkService extends BorhanBaseService
{
	const PARTNER_DEFAULT_CONVERSION_PROFILE_ID = -1;
	
	const SERVICE_NAME = "bulkUpload";

	/**
	 * Add new bulk upload batch job
	 * Conversion profile id can be specified in the API or in the CSV file, the one in the CSV file will be stronger.
	 * If no conversion profile was specified, partner's default will be used
	 * 
	 * @action addEntries
	 * @actionAlias media.bulkUploadAdd
	 * @param file $fileData
	 * @param BorhanBulkUploadType $bulkUploadType
	 * @param BorhanBulkUploadJobData $bulkUploadData
	 * @return BorhanBulkUpload
	 */
	function addEntriesAction($fileData, BorhanBulkUploadJobData $bulkUploadData = null, BorhanBulkUploadEntryData $bulkUploadEntryData = null)
	{
		if(get_class($bulkUploadData) == 'BorhanBulkUploadJobData')
			throw new BorhanAPIException(BorhanErrors::OBJECT_TYPE_ABSTRACT, 'BorhanBulkUploadJobData');
			
	    if($bulkUploadEntryData->conversionProfileId == self::PARTNER_DEFAULT_CONVERSION_PROFILE_ID)
			$bulkUploadEntryData->conversionProfileId = $this->getPartner()->getDefaultConversionProfileId();
	    
	    if (!$bulkUploadData)
	    {
	       $bulkUploadData = BorhanPluginManager::loadObject('BorhanBulkUploadJobData', null);
	    }
	    
	    if (!$bulkUploadEntryData)
	    {
	        $bulkUploadEntryData = new BorhanBulkUploadEntryData();
	    }
		if(!$bulkUploadData->fileName)
			$bulkUploadData->fileName = $fileData["name"];
		
		$dbBulkUploadJobData = $bulkUploadData->toInsertableObject();
		$bulkUploadCoreType = kPluginableEnumsManager::apiToCore("BulkUploadType", $bulkUploadData->type);
		/* @var $dbBulkUploadJobData kBulkUploadJobData */
		$dbBulkUploadJobData->setBulkUploadObjectType(BulkUploadObjectType::ENTRY);
		$dbBulkUploadJobData->setUserId($this->getKuser()->getPuserId());
		$dbObjectData = $bulkUploadEntryData->toInsertableObject();
		$dbBulkUploadJobData->setObjectData($dbObjectData);
		$dbBulkUploadJobData->setFilePath($fileData["tmp_name"]);
		
		$dbJob = kJobsManager::addBulkUploadJob($this->getPartner(), $dbBulkUploadJobData, $bulkUploadCoreType);
		$dbJobLog = BatchJobLogPeer::retrieveByBatchJobId($dbJob->getId());
		if(!$dbJobLog)
			return null;
			
		$bulkUpload = new BorhanBulkUpload();
		$bulkUpload->fromObject($dbJobLog, $this->getResponseProfile());
		
		return $bulkUpload;
	}
	
	/**
	 * @action addCategories
	 * @actionAlias category.addFromBulkUpload
	 * 
	 * Action adds categories from a bulkupload CSV file
	 * @param file $fileData
	 * @param BorhanBulkUploadJobData $bulkUploadData
	 * @param BorhanBulkUploadCategoryData $bulkUploadCategoryData
	 * @return BorhanBulkUpload
	 */
	public function addCategoriesAction ($fileData, BorhanBulkUploadJobData $bulkUploadData = null, BorhanBulkUploadCategoryData $bulkUploadCategoryData = null)
	{
	    if (!$bulkUploadData)
	    {
	       $bulkUploadData = BorhanPluginManager::loadObject('BorhanBulkUploadJobData', null);
	    }
	    
	    if (!$bulkUploadCategoryData)
	    {
	        $bulkUploadCategoryData = new BorhanBulkUploadCategoryData();
	    }
	    
		if(!$bulkUploadData->fileName)
			$bulkUploadData->fileName = $fileData["name"];
		
		$dbBulkUploadJobData = $bulkUploadData->toInsertableObject();
		$bulkUploadCoreType = kPluginableEnumsManager::apiToCore("BulkUploadType", $bulkUploadData->type);
		
		$dbBulkUploadJobData->setBulkUploadObjectType(BulkUploadObjectType::CATEGORY);
		$dbBulkUploadJobData->setUserId($this->getKuser()->getPuserId());
		$dbObjectData = $bulkUploadCategoryData->toInsertableObject();
		$dbBulkUploadJobData->setObjectData($dbObjectData);
		$dbBulkUploadJobData->setFilePath($fileData["tmp_name"]);
		
		$dbJob = kJobsManager::addBulkUploadJob($this->getPartner(), $dbBulkUploadJobData, $bulkUploadCoreType);
		$dbJobLog = BatchJobLogPeer::retrieveByBatchJobId($dbJob->getId());
		if(!$dbJobLog)
			return null;
		
		$bulkUpload = new BorhanBulkUpload();
		$bulkUpload->fromObject($dbJobLog, $this->getResponseProfile());
		
		return $bulkUpload;
	}
	
	/**
	 * @action addCategoryUsers
	 * @actionAlias categoryUser.addFromBulkUpload
	 * Action adds CategoryUsers from a bulkupload CSV file
	 * @param file $fileData
	 * @param BorhanBulkUploadJobData $bulkUploadData
	 * @param BorhanBulkUploadCategoryUserData $bulkUploadCategoryUserData
	 * @return BorhanBulkUpload
	 */
	public function addCategoryUsersAction ($fileData, BorhanBulkUploadJobData $bulkUploadData = null, BorhanBulkUploadCategoryUserData $bulkUploadCategoryUserData = null)
	{
	    if (!$bulkUploadData)
	    {
	       $bulkUploadData = BorhanPluginManager::loadObject('BorhanBulkUploadJobData', null);
	    }
	    
        if (!$bulkUploadCategoryUserData)
        {
            $bulkUploadCategoryUserData = new BorhanBulkUploadCategoryUserData();
        }
		
		if(!$bulkUploadData->fileName)
			$bulkUploadData->fileName = $fileData["name"];
		
		$dbBulkUploadJobData = $bulkUploadData->toInsertableObject();
		$bulkUploadCoreType = kPluginableEnumsManager::apiToCore("BulkUploadType", $bulkUploadData->type);
		$dbBulkUploadJobData->setBulkUploadObjectType(BulkUploadObjectType::CATEGORY_USER);
		$dbBulkUploadJobData->setUserId($this->getKuser()->getPuserId());
		$dbObjectData = $bulkUploadCategoryUserData->toInsertableObject();
		$dbBulkUploadJobData->setObjectData($dbObjectData);
		$dbBulkUploadJobData->setFilePath($fileData["tmp_name"]);
		
		$dbJob = kJobsManager::addBulkUploadJob($this->getPartner(), $dbBulkUploadJobData, $bulkUploadCoreType);
		$dbJobLog = BatchJobLogPeer::retrieveByBatchJobId($dbJob->getId());
		if(!$dbJobLog)
			return null;
		
		$bulkUpload = new BorhanBulkUpload();
		$bulkUpload->fromObject($dbJobLog, $this->getResponseProfile());
		
		return $bulkUpload;
	}
	
	/**
	 * @action addUsers
	 * @actionAlias user.addFromBulkUpload
	 * Action adds users from a bulkupload CSV file
	 * @param file $fileData
	 * @param BorhanBulkUploadJobData $bulkUploadData
	 * @param BorhanBulkUploadUserData $bulkUploadUserData
	 * @return BorhanBulkUpload
	 */
	public function addUsersAction($fileData, BorhanBulkUploadJobData $bulkUploadData = null, BorhanBulkUploadUserData $bulkUploadUserData = null)
	{
	   if (!$bulkUploadData)
	   {
	       $bulkUploadData = BorhanPluginManager::loadObject('BorhanBulkUploadJobData', null);
	   }
	   
	   if (!$bulkUploadUserData)
	   {
	       $bulkUploadUserData = new BorhanBulkUploadUserData();
	   }
		
		if(!$bulkUploadData->fileName)
			$bulkUploadData->fileName = $fileData["name"];
		
		$dbBulkUploadJobData = $bulkUploadData->toInsertableObject();
		$bulkUploadCoreType = kPluginableEnumsManager::apiToCore("BulkUploadType", $bulkUploadData->type);
		$dbBulkUploadJobData->setBulkUploadObjectType(BulkUploadObjectType::USER);
		$dbBulkUploadJobData->setUserId($this->getKuser()->getPuserId());
		$dbObjectData = $bulkUploadUserData->toInsertableObject();
		$dbBulkUploadJobData->setObjectData($dbObjectData);
		$dbBulkUploadJobData->setFilePath($fileData["tmp_name"]);
		
		$dbJob = kJobsManager::addBulkUploadJob($this->getPartner(), $dbBulkUploadJobData, $bulkUploadCoreType);
		$dbJobLog = BatchJobLogPeer::retrieveByBatchJobId($dbJob->getId());
		if(!$dbJobLog)
			return null;
			
		$bulkUpload = new BorhanBulkUpload();
		$bulkUpload->fromObject($dbJobLog, $this->getResponseProfile());
		
		return $bulkUpload;
	}
	
	/**
	 * @action addCategoryEntries
	 * @actionAlias categoryEntry.addFromBulkUpload
	 * Action adds active category entries
	 * @param BorhanBulkServiceData $bulkUploadData
	 * @param BorhanBulkUploadCategoryEntryData $bulkUploadCategoryEntryData
	 * @return BorhanBulkUpload
	 */
	public function addCategoryEntriesAction (BorhanBulkServiceData $bulkUploadData, BorhanBulkUploadCategoryEntryData $bulkUploadCategoryEntryData = null)
	{
		if($bulkUploadData instanceof  BorhanBulkServiceFilterData){
			if($bulkUploadData->filter instanceof BorhanBaseEntryFilter){
				if(	$bulkUploadData->filter->idEqual == null &&
					$bulkUploadData->filter->idIn == null &&
					$bulkUploadData->filter->categoriesIdsMatchOr == null &&
					$bulkUploadData->filter->categoriesMatchAnd == null &&
					$bulkUploadData->filter->categoriesMatchOr == null &&
					$bulkUploadData->filter->categoriesIdsMatchAnd == null)
						throw new BorhanAPIException(BorhanErrors::MUST_FILTER_ON_ENTRY_OR_CATEGORY);					
			}
			else if($bulkUploadData->filter instanceof BorhanCategoryEntryFilter){
				if(	$bulkUploadData->filter->entryIdEqual == null &&
					$bulkUploadData->filter->categoryIdIn == null &&
					$bulkUploadData->filter->categoryIdEqual == null )
						throw new BorhanAPIException(BorhanErrors::MUST_FILTER_ON_ENTRY_OR_CATEGORY);				
			}
		}
	   	$bulkUploadJobData = BorhanPluginManager::loadObject('BorhanBulkUploadJobData', $bulkUploadData->getType());
	   	$bulkUploadData->toBulkUploadJobData($bulkUploadJobData);
	    
        if (!$bulkUploadCategoryEntryData)
        {
            $bulkUploadCategoryEntryData = new BorhanBulkUploadCategoryEntryData();
        }
				
		$dbBulkUploadJobData = $bulkUploadJobData->toInsertableObject();
		$bulkUploadCoreType = kPluginableEnumsManager::apiToCore("BulkUploadType", $bulkUploadJobData->type);
		$dbBulkUploadJobData->setBulkUploadObjectType(BulkUploadObjectType::CATEGORY_ENTRY);
		$dbBulkUploadJobData->setUserId($this->getKuser()->getPuserId());
		$dbObjectData = $bulkUploadCategoryEntryData->toInsertableObject();
		$dbBulkUploadJobData->setObjectData($dbObjectData);
		
		$dbJob = kJobsManager::addBulkUploadJob($this->getPartner(), $dbBulkUploadJobData, $bulkUploadCoreType);
		$dbJobLog = BatchJobLogPeer::retrieveByBatchJobId($dbJob->getId());
		if(!$dbJobLog)
			return null;
		
		$bulkUpload = new BorhanBulkUpload();
		$bulkUpload->fromObject($dbJobLog, $this->getResponseProfile());
		
		return $bulkUpload;
	}	
	
	/**
	 * Get bulk upload batch job by id
	 *
	 * @action get
	 * @param int $id
	 * @return BorhanBulkUpload
	 */
	function getAction($id)
	{
	    $c = new Criteria();
	    $c->addAnd(BatchJobLogPeer::JOB_ID, $id);
		$c->addAnd(BatchJobLogPeer::PARTNER_ID, $this->getPartnerId());
		$c->addAnd(BatchJobLogPeer::JOB_TYPE, BatchJobType::BULKUPLOAD);
		$batchJob = BatchJobLogPeer::doSelectOne($c);
		
		if (!$batchJob)
		    throw new BorhanAPIException(BorhanErrors::BULK_UPLOAD_NOT_FOUND, $id);
		    
		$ret = new BorhanBulkUpload();
		$ret->fromObject($batchJob, $this->getResponseProfile());
		return $ret;
	}
	
	/**
	 * List bulk upload batch jobs
	 *
	 * @action list
	 * @param BorhanBulkUploadFilter $bulkUploadFilter
	 * @param BorhanFilterPager $pager
	 * @return BorhanBulkUploadListResponse
	 */
	function listAction(BorhanBulkUploadFilter $bulkUploadFilter = null, BorhanFilterPager $pager = null)
	{
	    if (!$bulkUploadFilter)
    	    $bulkUploadFilter = new BorhanBulkUploadFilter();
	    
	    if (!$pager)
			$pager = new BorhanFilterPager();
			
		
		$coreBulkUploadFilter = new BatchJobLogFilter();
        $bulkUploadFilter->toObject($coreBulkUploadFilter);
			
	    $c = new Criteria();
		$c->addAnd(BatchJobLogPeer::PARTNER_ID, $this->getPartnerId());
		$c->addAnd(BatchJobLogPeer::JOB_TYPE, BatchJobType::BULKUPLOAD);
		
		$crit = $c->getNewCriterion(BatchJobLogPeer::ABORT, null);
		$critOr = $c->getNewCriterion(BatchJobLogPeer::ABORT, 0);
		$crit->addOr($critOr);
		$c->add($crit);
		
		$c->addDescendingOrderByColumn(BatchJobLogPeer::ID);
		
		$coreBulkUploadFilter->attachToCriteria($c);
		$count = BatchJobLogPeer::doCount($c);
		$pager->attachToCriteria($c);
		$jobs = BatchJobLogPeer::doSelect($c);
		
		$response = new BorhanBulkUploadListResponse();
		$response->objects = BorhanBulkUploads::fromBatchJobArray($jobs);
		$response->totalCount = $count; 
		
		return $response;
	}
	
	
	
	
	/**
	 * serve action returns the original file.
	 * 
	 * @action serve
	 * @param int $id job id
	 * @return file
	 * 
	 */
	function serveAction($id)
	{
		$c = new Criteria();
		$c->addAnd(BatchJobPeer::ID, $id);
		$c->addAnd(BatchJobPeer::PARTNER_ID, $this->getPartnerId());
		$c->addAnd(BatchJobPeer::JOB_TYPE, BatchJobType::BULKUPLOAD);
		$batchJob = BatchJobPeer::doSelectOne($c);
		
		if (!$batchJob)
			throw new BorhanAPIException(BorhanErrors::BULK_UPLOAD_NOT_FOUND, $id);
			 
		BorhanLog::info("Batch job found for jobid [$id] bulk upload type [". $batchJob->getJobSubType() . "]");
		
		$syncKey = $batchJob->getSyncKey(BatchJob::FILE_SYNC_BATCHJOB_SUB_TYPE_BULKUPLOAD);
		list($fileSync, $local) = kFileSyncUtils::getReadyFileSyncForKey($syncKey, true, false);
		
		header("Content-Type: text/plain; charset=UTF-8");

		if($local)
		{
			$filePath = $fileSync->getFullPath();
			$mimeType = kFile::mimeType($filePath);
			return $this->dumpFile($filePath, $mimeType);
		}
		else
		{
			$remoteUrl = kDataCenterMgr::getRedirectExternalUrl($fileSync);
			BorhanLog::info("Redirecting to [$remoteUrl]");
			header("Location: $remoteUrl");
			die;
		}	
	}
	
	
	/**
	 * serveLog action returns the log file for the bulk-upload job.
	 * 
	 * @action serveLog
	 * @param int $id job id
	 * @return file
	 * 
	 */
	function serveLogAction($id)
	{
		$c = new Criteria();
		$c->addAnd(BatchJobPeer::ID, $id);
		$c->addAnd(BatchJobPeer::PARTNER_ID, $this->getPartnerId());
		$c->addAnd(BatchJobPeer::JOB_TYPE, BatchJobType::BULKUPLOAD);
		$batchJob = BatchJobPeer::doSelectOne($c);
		
		if (!$batchJob)
			throw new BorhanAPIException(BorhanErrors::BULK_UPLOAD_NOT_FOUND, $id);
			 
		BorhanLog::info("Batch job found for jobid [$id] bulk upload type [". $batchJob->getJobSubType() . "]");
			
		$pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanBulkUpload');
		foreach($pluginInstances as $pluginInstance)
		{
			/* @var $pluginInstance IBorhanBulkUpload */
			$pluginInstance->writeBulkUploadLogFile($batchJob);
		}	
	}
	
	/**
	 * Aborts the bulk upload and all its child jobs
	 * 
	 * @action abort
	 * @param int $id job id
	 * @return BorhanBulkUpload
	 */
	function abortAction($id)
	{
	    $c = new Criteria();
	    $c->addAnd(BatchJobPeer::ID, $id);
		$c->addAnd(BatchJobPeer::PARTNER_ID, $this->getPartnerId());
		$c->addAnd(BatchJobPeer::JOB_TYPE, BatchJobType::BULKUPLOAD);
		$batchJob = BatchJobPeer::doSelectOne($c);
		
	    if (!$batchJob)
		    throw new BorhanAPIException(BorhanErrors::BULK_UPLOAD_NOT_FOUND, $id);
		
		kJobsManager::abortJob($id, BatchJobType::BULKUPLOAD, true);
		
		$batchJobLog = BatchJobLogPeer::retrieveByBatchJobId($id);
		
		$ret = new BorhanBulkUpload();
		if ($batchJobLog)
    		$ret->fromObject($batchJobLog, $this->getResponseProfile());
    	
    	return $ret;
	}
	


}
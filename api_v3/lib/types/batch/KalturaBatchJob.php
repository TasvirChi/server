<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBatchJob extends BorhanObject implements IFilterable
{
	
	/**
	 * @var bigint
	 * @readonly
	 * @filter eq,gte
	 */
	public $id;
	
	/**
	 * @var int
	 * @readonly
	 * @filter eq,in,notin
	 */
	public $partnerId;
	
	
	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	/**
	 * @var time
	 * @readonly
	 */
	public $deletedAt;
	
	/**
	 * @var int
	 * @readonly
	 */
	public $lockExpiration;
	
	/**
	 * @var int
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $executionAttempts;
	
	/**
	 * @var int
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $lockVersion;
	
	/**
	 * @var string
	 * @filter eq
	 */
	public $entryId;
	
	/**
	 * @var string
	 */
	public $entryName;
	
	/**
	 * @var BorhanBatchJobType
	 * @readonly 
	 * @filter eq,in,notin
	 */
    public $jobType;
    
	/**
	 * @var int
	 * @filter eq,in,notin
	 */
    public $jobSubType;
    
	/**
	 * @var BorhanJobData
	 */
    public $data;

    /**
	 * @var BorhanBatchJobStatus
	 * @filter eq,in,notin,order
	 */
    public $status;
    
    /**
	 * @var int
	 */
    public $abort;
    
    /**
	 * @var int
	 */
    public $checkAgainTimeout;

    /**
	 * @var string
	 */
    public $message ;
    
    /**
	 * @var string
	 */
    public $description ;
    
    /**
	 * @var int
	 * @filter gte,lte,eq,in,notin,order
	 */
    public $priority ;
    
    /**
     * @var BorhanBatchHistoryDataArray
     */
    public $history ;
    
    /**
     * The id of the bulk upload job that initiated this job
	 * @var int
	 */    
    public $bulkJobId;
    
    /**
     * @var int
     * @filter gte,lte,eq
     */
    public $batchVersion;
    
    
    /**
     * When one job creates another - the parent should set this parentJobId to be its own id.
	 * @var int
	 */    
    public $parentJobId;
    
    
    /**
     * The id of the root parent job
	 * @var int
	 */    
    public $rootJobId;
    
    
    /**
     * The time that the job was pulled from the queue
	 * @var int
	 * @filter gte,lte,order
	 */    
    public $queueTime;
    
    
    /**
     * The time that the job was finished or closed as failed
	 * @var int
	 * @filter gte,lte,order
	 */    
    public $finishTime;
    
    
    /**
	 * @var BorhanBatchJobErrorTypes
	 * @filter eq,in,notin
	 */    
    public $errType;
    
    
    /**
	 * @var int
	 * @filter eq,in,notin
	 */    
    public $errNumber;
    
    
    /**
	 * @var int
	 * @filter lt,gt,order
	 */    
    public $estimatedEffort;
    
    /**
     * @var int
     * @filter lte,gte
     */
    public $urgency;
    
    /**
	 * @var int
	 */    
    public $schedulerId;
	
    
    /**
	 * @var int
	 */    
    public $workerId;
	
    
    /**
	 * @var int
	 */    
    public $batchIndex;
	
    
    /**
	 * @var int
	 */    
    public $lastSchedulerId;
	
    
    /**
	 * @var int
	 */    
    public $lastWorkerId;
    
    /**
	 * @var int
	 */    
    public $dc;
    
    /**
     * @var string
     */
    public $jobObjectId;

    /**
     * @var int
     */
	public $jobObjectType;
	
	private static $map_between_objects = array
	(
		"id" ,
		"partnerId" ,
		"createdAt" , "updatedAt" , 
		"entryId" ,
		"jobType" , 
	 	"status" ,  
		"message", "description" , "parentJobId" ,
		"rootJobId", "bulkJobId" , "priority" ,
		"queueTime" , "finishTime" ,  "errType", "errNumber", 
		"dc",
		"lastSchedulerId", "lastWorkerId" , 
		"history",
		"jobObjectId" => "objectId", "jobObjectType" => "objectType"
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	    
	public function fromStatisticsObject($dbBatchJob, $dbLockObj = null)
	{
		$dbBatchJobLock = BatchJobLockPeer::retrieveByPK($dbBatchJob->getId());
		$this->fromBatchJob($dbBatchJob, $dbBatchJobLock);
		
		if(!($dbBatchJob instanceof BatchJob))
			return $this;
			
		$entry = $dbBatchJob->getEntry(true);
		if($entry)
			$this->entryName = $entry->getName();
		
		return $this;
	}
	    
	public function fromData(BatchJob $dbBatchJob, $dbData)
	{
		if(!$dbData)
			return;
				
		switch(get_class($dbData))
		{
			case 'kConvartableJobData':
				$this->data = new BorhanConvartableJobData();
				break;
				
			case 'kConvertJobData':
				$this->data = new BorhanConvertJobData();
				break;
				
			case 'kConvertProfileJobData':
				$this->data = new BorhanConvertProfileJobData();
				break;
				
			case 'kExtractMediaJobData':
				$this->data = new BorhanExtractMediaJobData();
				break;
				
			case 'kImportJobData':
				$this->data = new BorhanImportJobData();
				break;
				
			case 'kSshImportJobData':
				$this->data = new BorhanSshImportJobData();
				break;
				
			case 'kPostConvertJobData':
				$this->data = new BorhanPostConvertJobData();
				break;
				
			case 'kMailJobData':
				$this->data = new BorhanMailJobData();
				break;
				
			case 'kNotificationJobData':
				$this->data = new BorhanNotificationJobData();
				break;
				
			case 'kBulkDownloadJobData':
				$this->data = new BorhanBulkDownloadJobData();
				break;
				
			case 'kFlattenJobData':
				$this->data = new BorhanFlattenJobData();
				break;
			
			case 'kProvisionJobData':
				$this->data = new BorhanProvisionJobData();
				break;
				
			case 'kAkamaiProvisionJobData':
				$this->data = new BorhanAkamaiProvisionJobData();
				break;	

			case 'kAkamaiUniversalProvisionJobData':
				$this->data = new BorhanAkamaiUniversalProvisionJobData();
				break;
				
			case 'kConvertCollectionJobData':
				$this->data = new BorhanConvertCollectionJobData();
				break;
				
			case 'kStorageExportJobData':
				$this->data = new BorhanStorageExportJobData();
				break;
				
			case 'kAmazonS3StorageExportJobData':
				$this->data = new BorhanAmazonS3StorageExportJobData();
				break;
				
			case 'kMoveCategoryEntriesJobData':
				$this->data = new BorhanMoveCategoryEntriesJobData();
				break;
				
			case 'kStorageDeleteJobData':
				$this->data = new BorhanStorageDeleteJobData();
				break;
				
			case 'kCaptureThumbJobData':
				$this->data = new BorhanCaptureThumbJobData();
				break;
				
			case 'kMoveCategoryEntriesJobData':
			    $this->data = new BorhanMoveCategoryEntriesJobData();
			    break;

			case 'kIndexJobData':
				$this->data = new BorhanIndexJobData();
				break;
				
			case 'kCopyJobData':
				$this->data = new BorhanCopyJobData();
				break;
				
			case 'kDeleteJobData':
				$this->data = new BorhanDeleteJobData();
				break;

			case 'kDeleteFileJobData':
				$this->data = new BorhanDeleteFileJobData();
				break;
				
			case 'kConvertLiveSegmentJobData':
				$this->data = new BorhanConvertLiveSegmentJobData();
				break;
				
			case 'kConcatJobData':
				$this->data = new BorhanConcatJobData();
				break;
				
			case 'kCopyPartnerJobData':
				$this->data = new BorhanCopyPartnerJobData();
				break;
				
			case 'kSyncCategoryPrivacyContextJobData':
				$this->data = new BorhanSyncCategoryPrivacyContextJobData();
				break;
			
			case 'kLiveReportExportJobData':
				$this->data = new BorhanLiveReportExportJobData();
				break;
			
			case 'kRecalculateResponseProfileCacheJobData':
				$this->data = new BorhanRecalculateResponseProfileCacheJobData();
				break;

			case 'kLiveToVodJobData':
				$this->data = new BorhanLiveToVodJobData();
				break;

			default:			
				if($dbData instanceof kBulkUploadJobData)
				{
					$this->data = BorhanPluginManager::loadObject('BorhanBulkUploadJobData', $dbBatchJob->getJobSubType());
					if(is_null($this->data))
						BorhanLog::err("Unable to init BorhanBulkUploadJobData for sub-type [" . $dbBatchJob->getJobSubType() . "]");
				}
				else if($dbData instanceof kImportJobData)
				{
					$this->data = BorhanPluginManager::loadObject('BorhanImportJobData', get_class($dbData));
					if(is_null($this->data))
						BorhanLog::err("Unable to init BorhanImportJobData for class [" . get_class($dbData) . "]");
				}
				else
				{
					$this->data = BorhanPluginManager::loadObject('BorhanJobData', $this->jobType, array('coreJobSubType' => $dbBatchJob->getJobSubType()));
				}
		}
		
		if(is_null($this->data))
			BorhanLog::err("Unable to init BorhanJobData for job type [{$this->jobType}] sub-type [" . $dbBatchJob->getJobSubType() . "]");
			
		if($this->data)
			$this->data->fromObject($dbData);
	}
	
	public function fromLockObject(BatchJob $dbBatchJob, BatchJobLock $dbBatchJobLock) 
	{
		$this->lockExpiration = $dbBatchJobLock->getExpiration();
		$this->executionAttempts = $dbBatchJobLock->getExecutionAttempts();
		$this->lockVersion = $dbBatchJobLock->getVersion();
		$this->checkAgainTimeout = $dbBatchJobLock->getStartAt(null);
		$this->estimatedEffort = $dbBatchJobLock->getEstimatedEffort();
		
		$this->schedulerId = $dbBatchJobLock->getSchedulerId();
		$this->workerId = $dbBatchJobLock->getWorkerId();
	}
	
	public function fromBatchJob($dbBatchJob, BatchJobLock $dbBatchJobLock = null) 
	{
		parent::fromObject($dbBatchJob);
		
		$this->queueTime = $dbBatchJob->getQueueTime(null); // to return the timestamp and not string
		$this->finishTime = $dbBatchJob->getFinishTime(null); // to return the timestamp and not string
		
		if(!($dbBatchJob instanceof BatchJob))
			return $this;
			
		$dbData = $dbBatchJob->getData();
		$this->fromData($dbBatchJob, $dbData);
		if($this->data)
			$this->jobSubType = $this->data->fromSubType($dbBatchJob->getJobSubType());
		
		if($dbBatchJobLock) {
			$this->fromLockObject($dbBatchJob, $dbBatchJobLock);
		} else {
			$this->lockVersion = $dbBatchJob->getLockInfo()->getLockVersion();
			$this->estimatedEffort = $dbBatchJob->getLockInfo()->getEstimatedEffort();
		}
		
		return $this;
	}
	
	public function toData(BatchJob $dbBatchJob)
	{
		$dbData = null;
		
		if(is_null($this->jobType))
			$this->jobType = kPluginableEnumsManager::coreToApi('BatchJobType', $dbBatchJob->getJobType());
		
		switch($dbBatchJob->getJobType())
		{
			case BorhanBatchJobType::BULKUPLOAD:
				$dbData = new kBulkUploadJobData();
				if(is_null($this->data))
					$this->data = new BorhanBulkUploadJobData();
				break;
				
			case BorhanBatchJobType::CONVERT:
				$dbData = new kConvertJobData();
				if(is_null($this->data))
					$this->data = new BorhanConvertJobData();
				break;
				
			case BorhanBatchJobType::CONVERT_PROFILE:
				$dbData = new kConvertProfileJobData();
				if(is_null($this->data))
					$this->data = new BorhanConvertProfileJobData();
				break;
				
			case BorhanBatchJobType::EXTRACT_MEDIA:
				$dbData = new kExtractMediaJobData();
				if(is_null($this->data))
					$this->data = new BorhanExtractMediaJobData();
				break;
				
			case BorhanBatchJobType::IMPORT:
				$dbData = new kImportJobData();
				if(is_null($this->data))
					$this->data = new BorhanImportJobData();
				break;
				
			case BorhanBatchJobType::POSTCONVERT:
				$dbData = new kPostConvertJobData();
				if(is_null($this->data))
					$this->data = new BorhanPostConvertJobData();
				break;
				
			case BorhanBatchJobType::MAIL:
				$dbData = new kMailJobData();
				if(is_null($this->data))
					$this->data = new BorhanMailJobData();
				break;
				
			case BorhanBatchJobType::NOTIFICATION:
				$dbData = new kNotificationJobData();
				if(is_null($this->data))
					$this->data = new BorhanNotificationJobData();
				break;
				
			case BorhanBatchJobType::BULKDOWNLOAD:
				$dbData = new kBulkDownloadJobData();
				if(is_null($this->data))
					$this->data = new BorhanBulkDownloadJobData();
				break;
				
			case BorhanBatchJobType::FLATTEN:
				$dbData = new kFlattenJobData();
				if(is_null($this->data))
					$this->data = new BorhanFlattenJobData();
				break;
				
			case BorhanBatchJobType::PROVISION_PROVIDE:
			case BorhanBatchJobType::PROVISION_DELETE:
				$jobSubType = $dbBatchJob->getJobSubType();
				$dbData = kAkamaiProvisionJobData::getInstance($jobSubType);
				if(is_null($this->data))
					$this->data = BorhanProvisionJobData::getJobDataInstance($jobSubType);

				break;
				
			case BorhanBatchJobType::CONVERT_COLLECTION:
				$dbData = new kConvertCollectionJobData();
				if(is_null($this->data))
					$this->data = new BorhanConvertCollectionJobData();
				break;
				
			case BorhanBatchJobType::STORAGE_EXPORT:
				$dbData = new kStorageExportJobData();
				if(is_null($this->data))
					$this->data = new BorhanStorageExportJobData();
				break;
				
			case BorhanBatchJobType::MOVE_CATEGORY_ENTRIES:
				$dbData = new kMoveCategoryEntriesJobData();
				if(is_null($this->data))
					$this->data = new BorhanMoveCategoryEntriesJobData();
				break;
				
			case BorhanBatchJobType::STORAGE_DELETE:
				$dbData = new kStorageDeleteJobData();
				if(is_null($this->data))
					$this->data = new BorhanStorageDeleteJobData();
				break;
				
			case BorhanBatchJobType::CAPTURE_THUMB:
				$dbData = new kCaptureThumbJobData();
				if(is_null($this->data))
					$this->data = new BorhanCaptureThumbJobData();
				break;
				
			case BorhanBatchJobType::INDEX:
				$dbData = new kIndexJobData();
				if(is_null($this->data))
					$this->data = new BorhanIndexJobData();
				break;
				
			case BorhanBatchJobType::COPY:
				$dbData = new kCopyJobData();
				if(is_null($this->data))
					$this->data = new BorhanCopyJobData();
				break;
				
			case BorhanBatchJobType::DELETE:
				$dbData = new kDeleteJobData();
				if(is_null($this->data))
					$this->data = new BorhanDeleteJobData();
				break;

			case BorhanBatchJobType::DELETE_FILE:
				$dbData = new kDeleteFileJobData();
				if(is_null($this->data))
					$this->data = new BorhanDeleteFileJobData();
				break;
				
			case BorhanBatchJobType::CONVERT_LIVE_SEGMENT:
				$dbData = new kConvertLiveSegmentJobData();
				if(is_null($this->data))
					$this->data = new BorhanConvertLiveSegmentJobData();
				break;
				
			case BorhanBatchJobType::CONCAT:
				$dbData = new kConcatJobData();
				if(is_null($this->data))
					$this->data = new BorhanConcatJobData();
				break;
					
			case BorhanBatchJobType::COPY_PARTNER:
				$dbData = new kCopyPartnerJobData();
				if(is_null($this->data))
					$this->data = new BorhanCopyPartnerJobData();
				break;
					
			case BorhanBatchJobType::RECALCULATE_CACHE:
				switch($dbBatchJob->getJobSubType())
				{
					case RecalculateCacheType::RESPONSE_PROFILE:
						$dbData = new kRecalculateResponseProfileCacheJobData();
						if(is_null($this->data))
							$this->data = new BorhanRecalculateResponseProfileCacheJobData();
						break;
				}
				break;
			
			case BorhanBatchJobType::LIVE_TO_VOD:
				$dbData = new kLiveToVodJobData();
				if(is_null($this->data))
					$this->data = new BorhanLiveToVodJobData();
 				break;
				
			default:
				$dbData = BorhanPluginManager::loadObject('kJobData', $dbBatchJob->getJobType());
				if(is_null($this->data)) {
					$this->data = BorhanPluginManager::loadObject('BorhanJobData', $this->jobType);
				}
		}
		
		if(is_null($dbBatchJob->getData()))
			$dbBatchJob->setData($dbData);
	
		if($this->data instanceof BorhanJobData)
		{
			$dbData = $this->data->toObject($dbBatchJob->getData());
			$dbBatchJob->setData($dbData);
		}
		
		return $dbData;
	}
	
	public function toObject($dbBatchJob = null, $props_to_skip = array())
	{
		if(is_null($dbBatchJob))
			$dbBatchJob = new BatchJob();

		$dbBatchJob = parent::toObject($dbBatchJob);
		if($this->abort)
			$dbBatchJob->setExecutionStatus(BatchJobExecutionStatus::ABORTED);
		
		if (!is_null($this->data))
		    $this->toData($dbBatchJob);
		if(!is_null($this->jobSubType) && $this->data instanceof BorhanJobData)
			$dbBatchJob->setJobSubType($this->data->toSubType($this->jobSubType));
		
		return $dbBatchJob;
	}   
	
	public function getExtraFilters()
	{
		return array();
	}
	
	public function getFilterDocs()
	{
		return array();
	} 
}

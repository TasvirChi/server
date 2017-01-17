<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanBatchJobFilter extends BorhanBatchJobBaseFilter
{
	protected function toDynamicJobSubTypeValues($jobType, $jobSubTypeIn)
	{
		$data = new BorhanJobData();
		switch($jobType)
		{
			case BorhanBatchJobType::BULKUPLOAD:
				$data = new BorhanBulkUploadJobData();
				break;
				
			case BorhanBatchJobType::CONVERT:
				$data = new BorhanConvertJobData();
				break;
				
			case BorhanBatchJobType::CONVERT_PROFILE:
				$data = new BorhanConvertProfileJobData();
				break;
				
			case BorhanBatchJobType::EXTRACT_MEDIA:
				$data = new BorhanExtractMediaJobData();
				break;
				
			case BorhanBatchJobType::IMPORT:
				$data = new BorhanImportJobData();
				break;
				
			case BorhanBatchJobType::POSTCONVERT:
				$data = new BorhanPostConvertJobData();
				break;
				
			case BorhanBatchJobType::MAIL:
				$data = new BorhanMailJobData();
				break;
				
			case BorhanBatchJobType::NOTIFICATION:
				$data = new BorhanNotificationJobData();
				break;
				
			case BorhanBatchJobType::BULKDOWNLOAD:
				$data = new BorhanBulkDownloadJobData();
				break;
				
			case BorhanBatchJobType::FLATTEN:
				$data = new BorhanFlattenJobData();
				break;
				
			case BorhanBatchJobType::PROVISION_PROVIDE:
			case BorhanBatchJobType::PROVISION_DELETE:	
				$data = new BorhanProvisionJobData();
				break;
				
			case BorhanBatchJobType::CONVERT_COLLECTION:
				$data = new BorhanConvertCollectionJobData();
				break;
				
			case BorhanBatchJobType::STORAGE_EXPORT:
				$data = new BorhanStorageExportJobData();
				break;
				
			case BorhanBatchJobType::STORAGE_DELETE:
				$data = new BorhanStorageDeleteJobData();
				break;
				
			case BorhanBatchJobType::INDEX:
				$data = new BorhanIndexJobData();
				break;
				
			case BorhanBatchJobType::COPY:
				$data = new BorhanCopyJobData();
				break;
				
			case BorhanBatchJobType::DELETE:
				$data = new BorhanDeleteJobData();
				break;

			case BorhanBatchJobType::DELETE_FILE:
				$data = new BorhanDeleteFileJobData();
				break;
				
			case BorhanBatchJobType::MOVE_CATEGORY_ENTRIES:
				$data = new BorhanMoveCategoryEntriesJobData();
				break;
				
			default:
				$data = BorhanPluginManager::loadObject('BorhanJobData', $jobType);
		}
		
		if(!$data)
		{
			BorhanLog::err("Data type not found for job type [$jobType]");
			return null;
		}
			
		$jobSubTypeArray = explode(baseObjectFilter::IN_SEPARATOR, $jobSubTypeIn);
		$dbJobSubTypeArray = array();
		foreach($jobSubTypeArray as $jobSubType)
			$dbJobSubTypeArray[] = $data->toSubType($jobSubType);
			
		$dbJobSubType = implode(baseObjectFilter::IN_SEPARATOR, $dbJobSubTypeArray);
		return $dbJobSubType;
	}

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new BatchJobFilter();
	}
	
	/**
	 * @param int $jobType
	 * @return BatchJobFilter
	 */
	public function toFilter($jobType = null)
	{
		$batchJobFilter = $this->toObject(new BatchJobFilter(false));
		
		if(!is_null($jobType) && !is_null($this->jobSubTypeIn))
		{
			$jobSubTypeIn = $this->toDynamicJobSubTypeValues($jobType, $this->jobSubTypeIn);
			$batchJobFilter->set('_in_job_sub_type', $jobSubTypeIn);
		}
	
		if(!is_null($jobType) && !is_null($this->jobSubTypeNotIn))
		{
			$jobSubTypeNotIn = $this->toDynamicJobSubTypeValues($jobType, $this->jobSubTypeNotIn);
			$batchJobFilter->set('_notin_job_sub_type', $jobSubTypeNotIn);
		}
		
		return $batchJobFilter;
	}
}

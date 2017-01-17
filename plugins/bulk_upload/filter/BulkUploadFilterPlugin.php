<?php
/**
 * @package plugins.bulkUploadFilter
 */
class BulkUploadFilterPlugin extends BorhanPlugin implements IBorhanBulkUpload, IBorhanPending
{
	const PLUGIN_NAME = 'bulkUploadFilter';
	
	/**
	 *
	 * Returns the plugin name
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}

	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$drmDependency = new BorhanDependency(BulkUploadPlugin::PLUGIN_NAME);
		
		return array($drmDependency);
	}
	
	/**
	 * @return array<string> list of enum classes names that extend the base enum name
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('BulkUploadFilterType');
	
		if($baseEnumName == 'BulkUploadType')
			return array('BulkUploadFilterType');
		
		return array();
	}
	
	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @param array $constructorArgs
	 * @return object
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		 //Gets the right job for the engine
		if($baseClass == 'kBulkUploadJobData' && (!$enumValue || $enumValue == self::getBulkUploadTypeCoreValue(BulkUploadFilterType::FILTER)))
			return new kBulkUploadFilterJobData();
		
		 //Gets the right job for the engine
		if($baseClass == 'BorhanBulkUploadJobData' && (!$enumValue || $enumValue == self::getBulkUploadTypeCoreValue(BulkUploadFilterType::FILTER)))
			return new BorhanBulkUploadFilterJobData();
			
		 //Gets the service data for the engine
//		if($baseClass == 'BorhanBulkServiceData' && (!$enumValue || $enumValue == self::getBulkUploadTypeCoreValue(BulkUploadFilterType::FILTER)))
//			return new BorhanBulkServiceFilterData();
			
		
		//Gets the engine (only for clients)
		if($baseClass == 'KBulkUploadEngine' && class_exists('BorhanClient') && (!$enumValue || $enumValue == BorhanBulkUploadType::FILTER))
		{
			list($job) = $constructorArgs;
			/* @var $job BorhanBatchJob */
			switch ($job->data->bulkUploadObjectType)
			{
			    case BorhanBulkUploadObjectType::CATEGORY_ENTRY:
			        return new BulkUploadCategoryEntryEngineFilter($job);
			    default:
			        throw new BorhanException("Bulk upload object type [{$job->data->bulkUploadObjectType}] not found", BorhanBatchJobAppErrors::ENGINE_NOT_FOUND);
			        break;
			}
			
		}
				
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		return null;
	}
	
	/**
	 * Returns the correct file extension for bulk upload type
	 * @param int $enumValue code API value
	 */
	public static function getFileExtension($enumValue)
	{
		if($enumValue == self::getBulkUploadTypeCoreValue(BulkUploadFilterType::FILTER))
			return null;
	}
	
	
	/**
	 * Returns the log file for bulk upload job
	 * @param BatchJob $batchJob bulk upload batchjob
	 */
	public static function writeBulkUploadLogFile($batchJob)
	{
		if($batchJob->getJobSubType() && ($batchJob->getJobSubType() != self::getBulkUploadTypeCoreValue(BulkUploadFilterType::FILTER))){
			return;
		}
		//TODO:
		header("Content-Type: text/plain; charset=UTF-8");
				$criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $batchJob->getId());
		$criteria->addAscendingOrderByColumn(BulkUploadResultPeer::LINE_INDEX);
		$criteria->setLimit(100);
		$bulkUploadResults = BulkUploadResultPeer::doSelect($criteria);
		
		if(!count($bulkUploadResults))
			die("Log file is not ready");
			
		$STDOUT = fopen('php://output', 'w');
		$data = $batchJob->getData();
        /* @var $data kBulkUploadFilterJobData */		
		$handledResults = 0;
		while(count($bulkUploadResults))
		{
			$handledResults += count($bulkUploadResults);
			foreach($bulkUploadResults as $bulkUploadResult)
			{				
	            $values = array();
	            $values['bulkUploadResultStatus'] = $bulkUploadResult->getStatus();
				$values['objectId'] = $bulkUploadResult->getObjectId();
				$values['objectStatus'] = $bulkUploadResult->getObjectStatus();
				$values['errorDescription'] = preg_replace('/[\n\r\t]/', ' ', $bulkUploadResult->getErrorDescription());
					
				fwrite($STDOUT, print_r($values,true));
			}
			
    		if(count($bulkUploadResults) < $criteria->getLimit())
    			break;
	    		
    		kMemoryManager::clearMemory();
    		$criteria->setOffset($handledResults);
			$bulkUploadResults = BulkUploadResultPeer::doSelect($criteria);
		}
		fclose($STDOUT);
		
		kFile::closeDbConnections();
		exit;
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getBulkUploadTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('BulkUploadType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}

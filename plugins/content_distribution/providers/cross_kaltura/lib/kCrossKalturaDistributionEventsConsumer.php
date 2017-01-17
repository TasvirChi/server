<?php
/**
 * @package plugins.crossBorhanDistribution
 * @subpackage lib
 */
class kCrossBorhanDistributionEventsConsumer implements kBatchJobStatusEventConsumer
{
	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::shouldConsumeJobStatusEvent()
	 */
	public function shouldConsumeJobStatusEvent(BatchJob $dbBatchJob)
	{		
		$jobTypes = array(
			ContentDistributionPlugin::getBatchJobTypeCoreValue(ContentDistributionBatchJobType::DISTRIBUTION_SUBMIT),
			ContentDistributionPlugin::getBatchJobTypeCoreValue(ContentDistributionBatchJobType::DISTRIBUTION_UPDATE),
		);
		
	    if(!in_array($dbBatchJob->getJobType(), $jobTypes))
	    {	
            // wrong job type
			return false;
		}
	    
	    $data = $dbBatchJob->getData();
		if (!$data instanceof kDistributionJobData)
		{	
		    BorhanLog::err('Wrong job data type');
			return false;
		}	
		
		$crossBorhanCoreValueType = kPluginableEnumsManager::apiToCore('DistributionProviderType', CrossBorhanDistributionPlugin::getApiValue(CrossBorhanDistributionProviderType::CROSS_BORHAN));
		if ($data->getProviderType() == $crossBorhanCoreValueType)
		{		
			return true;
		}		
		
		// not the right provider
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::updatedJob()
	 */
	public function updatedJob(BatchJob $dbBatchJob, BatchJob $twinJob = null)
	{		
		if ($dbBatchJob->getStatus() == BatchJob::BATCHJOB_STATUS_FINISHED)
		{				
			return self::onDistributionJobFinished($dbBatchJob);
		}
		
		return true;
	}

	/**
	 * @param BatchJob $dbBatchJob
	 * @return BatchJob
	 */
	public static function onDistributionJobFinished(BatchJob $dbBatchJob)
	{
	    $data = $dbBatchJob->getData();
	    
		$entryDistribution = EntryDistributionPeer::retrieveByPK($data->getEntryDistributionId());
		if(!$entryDistribution)
		{
			BorhanLog::err('Entry distribution ['.$data->getEntryDistributionId().'] not found');
			return $dbBatchJob;
		}
		
		$providerData = $data->getProviderData();
		if(!($providerData instanceof kCrossBorhanDistributionJobProviderData))
		{
		    BorhanLog::err('Wrong provider data class ['.get_class($providerData).']');
			return $dbBatchJob;
		}
		
		$entryDistribution->putInCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_FLAVOR_ASSETS, $providerData->getDistributedFlavorAssets());
		$entryDistribution->putInCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_THUMB_ASSETS, $providerData->getDistributedThumbAssets());
		$entryDistribution->putInCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_METADATA, $providerData->getDistributedMetadata());
		$entryDistribution->putInCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_CAPTION_ASSETS, $providerData->getDistributedCaptionAssets());
		$entryDistribution->putInCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_CUE_POINTS, $providerData->getDistributedCuePoints());
		$entryDistribution->save();
		
		return $dbBatchJob;
	}
	
}
<?php

class KAsyncWidevineRepositorySync extends KJobHandlerWorker
{	
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::WIDEVINE_REPOSITORY_SYNC;
	}
	
	/* (non-PHPdoc)
	 * @see KBatchBase::getJobType()
	 */
	public function getJobType()
	{
		return self::getType();
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->syncRepository($job, $job->data);			
	}

	protected function syncRepository(BorhanBatchJob $job, BorhanWidevineRepositorySyncJobData $data)
	{
		$job = $this->updateJob($job, "Start synchronization of Widevine repository", BorhanBatchJobStatus::QUEUED);
				
		switch ($data->syncMode)
		{
			case BorhanWidevineRepositorySyncMode::MODIFY:
				$this->sendModifyRequest($job, $data);
				break;
			default:
				throw new kApplicativeException(null, "Unknown sync mode [".$data->syncMode. "]");
		}

		return $this->closeJob($job, null, null, "Sync request sent successfully", BorhanBatchJobStatus::FINISHED, $data);
	}		

	/**
	 * Send asset notify request to VOD Dealer to update widevine assets
	 * 
	 * @param BorhanBatchJob $job
	 * @param BorhanWidevineRepositorySyncJobData $data
	 */
	private function sendModifyRequest(BorhanBatchJob $job, BorhanWidevineRepositorySyncJobData $data)
	{
		$dataWrap = new WidevineRepositorySyncJobDataWrap($data);		
		$widevineAssets = $dataWrap->getWidevineAssetIds();
		$licenseStartDate = $dataWrap->getLicenseStartDate();
		$licenseEndDate = $dataWrap->getLicenseEndDate();

		$this->impersonate($job->partnerId);

		$drmPlugin = BorhanDrmClientPlugin::get(KBatchBase::$kClient);
		$profile = $drmPlugin->drmProfile->getByProvider(BorhanDrmProviderType::WIDEVINE);

		foreach ($widevineAssets as $assetId) 
		{
			$this->updateWidevineAsset($assetId, $licenseStartDate, $licenseEndDate, $profile);
		}
		
		if($data->monitorSyncCompletion)
			$this->updateFlavorAssets($job, $dataWrap);

		$this->unimpersonate();
	}
	
	/**
	 * Execute register asset with new details to update exisiting asset
	 * 
	 * @param int $assetId
	 * @param string $licenseStartDate
	 * @param string $licenseEndDate
	 * @throws kApplicativeException
	 */
	private function updateWidevineAsset($assetId, $licenseStartDate, $licenseEndDate, $profile)
	{
		BorhanLog::debug("Update asset [".$assetId."] license start date [".$licenseStartDate.'] license end date ['.$licenseEndDate.']');
		
		$errorMessage = '';
		
		$wvAssetId = KWidevineBatchHelper::sendRegisterAssetRequest(
										$profile->regServerHost,
										null,
										$assetId,
										$profile->portal,
										null,
										$licenseStartDate,
										$licenseEndDate,
										$profile->iv, 
										$profile->key, 									
										$errorMessage);				
		
		if(!$wvAssetId)
		{
			KBatchBase::unimpersonate();
			
			$logMessage = 'Asset update failed, asset id: '.$assetId.' error: '.$errorMessage;
			BorhanLog::err($logMessage);
			throw new kApplicativeException(null, $logMessage);
		}			
	}
	
	/**
	 * Update flavorAsset in Borhan after the distribution dates apllied to Wideivne asset
	 * 
	 * @param BorhanBatchJob $job
	 * @param WidevineRepositorySyncJobDataWrap $dataWrap
	 */
	private function updateFlavorAssets(BorhanBatchJob $job, WidevineRepositorySyncJobDataWrap $dataWrap)
	{	
		$startDate = $dataWrap->getLicenseStartDate();
		$endDate = $dataWrap->getLicenseEndDate();	
		
		$filter = new BorhanAssetFilter();
		$filter->entryIdEqual = $job->entryId;
		$filter->tagsLike = 'widevine';
		$flavorAssetsList = self::$kClient->flavorAsset->listAction($filter, new BorhanFilterPager());
		
		foreach ($flavorAssetsList->objects as $flavorAsset) 
		{
			if($flavorAsset instanceof BorhanWidevineFlavorAsset && $dataWrap->hasAssetId($flavorAsset->widevineAssetId))
			{
				$updatedFlavorAsset = new BorhanWidevineFlavorAsset();
				$updatedFlavorAsset->widevineDistributionStartDate = $startDate;
				$updatedFlavorAsset->widevineDistributionEndDate = $endDate;
				self::$kClient->flavorAsset->update($flavorAsset->id, $updatedFlavorAsset);
			}		
		}		
	}
}

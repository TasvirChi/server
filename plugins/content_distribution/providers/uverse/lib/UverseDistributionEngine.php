<?php
/**
 * @package plugins.uverseDistribution
 * @subpackage lib
 */
class UverseDistributionEngine extends DistributionEngine implements 
	IDistributionEngineUpdate,
	IDistributionEngineSubmit,
	IDistributionEngineDelete
{
	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanUverseDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanUverseDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanUverseDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUverseDistributionJobProviderData");
		
		$this->sendFile($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineDelete::delete()
	 */
	public function delete(BorhanDistributionDeleteJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanUverseDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanUverseDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanUverseDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUverseDistributionJobProviderData");
		
		$this->handleDelete($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanUverseDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanUverseDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanUverseDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUverseDistributionJobProviderData");
		
		$this->sendFile($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanUverseDistributionProfile $distributionProfile
	 * @param BorhanUverseDistributionJobProviderData $providerData
	 */
	protected function sendFile(BorhanDistributionJobData $data, BorhanUverseDistributionProfile $distributionProfile, BorhanUverseDistributionJobProviderData $providerData)
	{
		$ftpManager = $this->getFTPManager($distributionProfile);
		
		$providerData->remoteAssetFileName = $this->getRemoteFileName($distributionProfile, $providerData);
		$providerData->remoteAssetUrl = $this->getRemoteUrl($distributionProfile, $providerData);
		if ($ftpManager->fileExists($providerData->remoteAssetFileName))
			BorhanLog::err('The file ['.$providerData->remoteAssetFileName.'] already exists at the FTP');
		else
			$ftpManager->putFile($providerData->remoteAssetFileName, $providerData->localAssetFilePath);
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanUverseDistributionProfile $distributionProfile
	 * @param BorhanUverseDistributionJobProviderData $providerData
	 */
	protected function handleDelete(BorhanDistributionJobData $data, BorhanUverseDistributionProfile $distributionProfile, BorhanUverseDistributionJobProviderData $providerData)
	{
		$ftpManager = $this->getFTPManager($distributionProfile);
		$ftpManager->delFile($providerData->remoteAssetFileName);
	}
	
	/**
	 * 
	 * @param BorhanUverseDistributionProfile $distributionProfile
	 * @return ftpMgr
	 */
	protected function getFTPManager(BorhanUverseDistributionProfile $distributionProfile)
	{
		$host = $distributionProfile->ftpHost;
		$login = $distributionProfile->ftpLogin;
		$password = $distributionProfile->ftpPassword;
		$engineOptions = isset(KBatchBase::$taskConfig->engineOptions) ? KBatchBase::$taskConfig->engineOptions->toArray() : array();
		$ftpManager = kFileTransferMgr::getInstance(kFileTransferMgrType::FTP, $engineOptions);
		$ftpManager->login($host, $login, $password);
		return $ftpManager;
	}
	
	/**
	 * @param BorhanUverseDistributionProfile $distributionProfile
	 * @param BorhanUverseDistributionJobProviderData $providerData
	 * @return string
	 */
	protected function getRemoteFileName(BorhanUverseDistributionProfile $distributionProfile, BorhanUverseDistributionJobProviderData $providerData)
	{
		return pathinfo($providerData->localAssetFilePath, PATHINFO_BASENAME);
	}
	
	/**
	 * @param BorhanUverseDistributionProfile $distributionProfile
	 * @param BorhanUverseDistributionJobProviderData $providerData
	 * @return string
	 */
	protected function getRemoteUrl(BorhanUverseDistributionProfile $distributionProfile, BorhanUverseDistributionJobProviderData $providerData)
	{
		$remoteFileName = $this->getRemoteFileName($distributionProfile, $providerData);
		return 'ftp://'.$distributionProfile->ftpHost.'/'.$remoteFileName;
	}
}
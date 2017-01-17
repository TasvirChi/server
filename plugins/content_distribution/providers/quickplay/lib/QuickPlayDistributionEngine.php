<?php
/**
 * @package plugins.quickPlayDistribution
 * @subpackage lib
 */
class QuickPlayDistributionEngine extends DistributionEngine implements 
	IDistributionEngineSubmit,
	IDistributionEngineUpdate
{
	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @throws Exception
	 */
	protected function validateJobDataObjectTypes(BorhanDistributionJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanQuickPlayDistributionProfile))
			throw new Exception("Distribution profile must be of type BorhanQuickPlayDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanQuickPlayDistributionJobProviderData))
			throw new Exception("Provider data must be of type BorhanQuickPlayDistributionJobProviderData");
	}
	
	/**
	 * @param string $path
	 * @param BorhanDistributionJobData $data
	 * @param BorhanVerizonDistributionProfile $distributionProfile
	 * @param BorhanVerizonDistributionJobProviderData $providerData
	 */
	public function handleSubmit(BorhanDistributionJobData $data, BorhanQuickPlayDistributionProfile $distributionProfile, BorhanQuickPlayDistributionJobProviderData $providerData)
	{
		$fileName = $data->entryDistribution->entryId . '_' . date('Y-m-d_H-i-s') . '.xml';
		BorhanLog::info('Sending file '. $fileName);
		
		$sftpManager = $this->getSFTPManager($distributionProfile);
		
		// upload the thumbnails
		foreach($providerData->thumbnailFilePaths as $thumbnailFilePath)
		{
			/* @var $thumbnailFilePath BorhanString */
			if (!file_exists($thumbnailFilePath->value))
				throw new BorhanDistributionException('Thumbnail file path ['.$thumbnailFilePath.'] not found, assuming it wasn\'t synced and the job will retry');

			$thumbnailUploadPath = '/'.$distributionProfile->sftpBasePath.'/'.pathinfo($thumbnailFilePath->value, PATHINFO_BASENAME);
			if ($sftpManager->fileExists($thumbnailUploadPath))
				BorhanLog::info('File "'.$thumbnailUploadPath.'" already exists, skip it');
			else
				$sftpManager->putFile($thumbnailUploadPath, $thumbnailFilePath->value);
		}
		
		// upload the video files
		foreach($providerData->videoFilePaths as $videoFilePath)
		{
			/* @var $videoFilePath BorhanString */
			if (!file_exists($videoFilePath->value))
				throw new BorhanDistributionException('Video file path ['.$videoFilePath.'] not found, assuming it wasn\'t synced and the job will retry');

			$videoUploadPath = '/'.$distributionProfile->sftpBasePath.'/'.pathinfo($videoFilePath->value, PATHINFO_BASENAME);
			if ($sftpManager->fileExists($videoUploadPath))
				BorhanLog::info('File "'.$videoUploadPath.'" already exists, skip it');
			else
				$sftpManager->putFile($videoUploadPath, $videoFilePath->value);
		}

		$tmpfile = tempnam(sys_get_temp_dir(), time());
		file_put_contents($tmpfile, $providerData->xml);
		// upload the metadata file
		$res = $sftpManager->putFile('/'.$distributionProfile->sftpBasePath.'/'.$fileName, $tmpfile);
		unlink($tmpfile);
				
		if ($res === false)
			throw new Exception('Failed to upload metadata file to sftp');
			
		$data->remoteId = $fileName;
		$data->sentData = $providerData->xml;
	}
	
	/**
	 * 
	 * @param BorhanQuickPlayDistributionProfile $distributionProfile
	 * @return sftpMgr
	 */
	protected function getSFTPManager(BorhanQuickPlayDistributionProfile $distributionProfile)
	{
		$host = $distributionProfile->sftpHost;
		$login = $distributionProfile->sftpLogin;
		$pass = $distributionProfile->sftpPass;
		$engineOptions = isset(KBatchBase::$taskConfig->engineOptions) ? KBatchBase::$taskConfig->engineOptions->toArray() : array();
		$sftpManager = kFileTransferMgr::getInstance(kFileTransferMgrType::SFTP, $engineOptions);
		$sftpManager->login($host, $login, $pass);
		return $sftpManager;
	}
}
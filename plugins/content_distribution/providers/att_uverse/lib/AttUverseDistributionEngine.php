<?php
/**
 * @package plugins.attUverseDistribution
 * @subpackage lib
 */
class AttUverseDistributionEngine extends DistributionEngine implements 
	IDistributionEngineUpdate,
	IDistributionEngineSubmit	
{
	
	const FEED_TEMPLATE = 'feed_template.xml';

	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanAttUverseDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanAttUverseDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanAttUverseDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanAttUverseDistributionJobProviderData");
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanAttUverseDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanAttUverseDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanUverseDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUverseDistributionJobProviderData");
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanAttUverseDistributionProfile $distributionProfile
	 * @param BorhanAttUverseDistributionJobProviderData $providerData
	 */
	protected function handleSubmit(BorhanDistributionJobData $data, BorhanAttUverseDistributionProfile $distributionProfile, BorhanAttUverseDistributionJobProviderData $providerData)
	{
		/* @var $entryDistribution EntryDistribution */
		$entryDistribution = $data->entryDistribution;	

		$remoteId = $entryDistribution->entryId;
		$data->remoteId = $remoteId;
							
		$ftpManager = $this->getFTPManager($distributionProfile);
		if(!$ftpManager)
			throw new Exception("FTP manager not loaded");		
			
		//upload video to FTP
		$remoteAssetFileUrls = array();
		$remoteThumbnailFileUrls = array();
		$remoteCaptionFileUrls = array();
		/* @var $file BorhanAttUverseDistributionFile */
		foreach ($providerData->filesForDistribution as $file){
			$ftpPath = $distributionProfile->ftpPath;
			$destFilePath = $ftpPath ?  $ftpPath.DIRECTORY_SEPARATOR.$file->remoteFilename: $file->remoteFilename;	
			$this->uploadAssetsFiles($ftpManager, $destFilePath, $file->localFilePath);
			if ($file->assetType == BorhanAssetType::FLAVOR)
				$remoteAssetFileUrls[$file->assetId] = 'ftp://'.$distributionProfile->ftpHost.'/'.$destFilePath;
			if ( $file->assetType == BorhanAssetType::THUMBNAIL)
				$remoteThumbnailFileUrls[$file->assetId] = 'ftp://'.$distributionProfile->ftpHost.'/'.$destFilePath;
			if ( ($file->assetType == BorhanAssetType::ATTACHMENT) ||($file->assetType == BorhanAssetType::CAPTION))
				$remoteCaptionFileUrls[$file->assetId] = 'ftp://'.$distributionProfile->ftpHost.'/'.$destFilePath;
		}
		
		//save flavor assets on provider data to use in the service				
		$providerData->remoteAssetFileUrls = serialize($remoteAssetFileUrls);
		//save thumnail assets on provider data to use in the service
		$providerData->remoteThumbnailFileUrls = serialize($remoteThumbnailFileUrls);
		//save caption assets on provider data to use in the service
		$providerData->remoteCaptionFileUrls = serialize($remoteCaptionFileUrls);
		

	}	
	
	/**
	 * 
	 * @param BorhanAttUverseDistributionProfile $distributionProfile
	 * @return ftpMgr
	 */
	protected function getFTPManager(BorhanAttUverseDistributionProfile $distributionProfile)
	{
		$host = $distributionProfile->ftpHost;
		$login = $distributionProfile->ftpUsername;
		$password = $distributionProfile->ftpPassword;
		$engineOptions = isset(KBatchBase::$taskConfig->engineOptions) ? KBatchBase::$taskConfig->engineOptions->toArray() : array();
		$ftpManager = kFileTransferMgr::getInstance(kFileTransferMgrType::FTP, $engineOptions);
		$ftpManager->login($host, $login, $password);
		return $ftpManager;
	}
	
	
	protected function uploadAssetsFiles($ftpManager, $destFileName, $filePath)
	{									
		if ($ftpManager->fileExists($destFileName))
		{
			BorhanLog::err('The file ['.$destFileName.'] already exists at the FTP');
		}
		else	
		{					
			$ftpManager->putFile($destFileName, $filePath, true);
		}
	}
	

}
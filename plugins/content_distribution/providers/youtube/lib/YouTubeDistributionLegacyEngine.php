<?php
/**
 * @package plugins.youTubeDistribution
 * @subpackage lib
 */
class YouTubeDistributionLegacyEngine extends DistributionEngine implements
	IDistributionEngineUpdate,
	IDistributionEngineSubmit,
	IDistributionEngineReport,
	IDistributionEngineDelete,
	IDistributionEngineCloseUpdate,
	IDistributionEngineCloseSubmit,
	IDistributionEngineCloseDelete
{
	const TEMP_DIRECTORY = 'youtube_distribution';
	const FEED_TEMPLATE = 'feed_template.xml';

	/**
	 * @var sftpMgr
	 */
	protected $_sftpManager;

	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanYouTubeDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanYouTubeDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanYouTubeDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanYouTubeDistributionJobProviderData");
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return false;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseSubmit::closeSubmit()
	 */
	public function closeSubmit(BorhanDistributionSubmitJobData $data)
	{
		$statusXml = $this->fetchStatusXml($data, $data->distributionProfile, $data->providerData);

		if ($statusXml === false) // no status yet
		{
			// try to get batch status xml to see if there is an internal error on youtube's batch
			$batchStatus = $this->fetchBatchStatus($data, $data->distributionProfile, $data->providerData);
			if ($batchStatus)
				throw new Exception('Internal failure on YouTube, internal_failure-status.xml was found. Error ['.$batchStatus.']');

			return false;
		}
			
		$statusParser = new YouTubeDistributionLegacyStatusParser($statusXml);
		$status = $statusParser->getStatusForCommand('Insert');
		$statusDetail = $statusParser->getStatusDetailForCommand('Insert');
		if (is_null($status))
		{
			// try to get the status of Parse command
			$status = $statusParser->getStatusForCommand('Parse');
			$statusDetail = $statusParser->getStatusDetailForCommand('Parse');
			if (!is_null($status))
				throw new Exception('Distribution failed on parsing command with status ['.$status.'] and error ['.$statusDetail.']');
			else
				throw new Exception('Status could not be found after distribution submission');
		}
		
		if ($status != 'Success')
			throw new Exception('Distribution failed with status ['.$status.'] and error ['.$statusDetail.']');
			
		$remoteId = $statusParser->getRemoteId();
		if (is_null($remoteId))
			throw new Exception('Remote id was not found after distribution submission');
		
		$data->remoteId = $remoteId;
			
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineDelete::delete()
	 */
	public function delete(BorhanDistributionDeleteJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanYouTubeDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanYouTubeDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanYouTubeDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanYouTubeDistributionJobProviderData");
		
		$this->handleDelete($data, $data->distributionProfile, $data->providerData);
		
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseDelete::closeDelete()
	 */
	public function closeDelete(BorhanDistributionDeleteJobData $data)
	{
		$statusXml = $this->fetchStatusXml($data, $data->distributionProfile, $data->providerData);

		if ($statusXml === false) // no status yet
			return false;
			
		$statusParser = new YouTubeDistributionLegacyStatusParser($statusXml);
		$status = $statusParser->getStatusForCommand('Delete');
		$statusDetail = $statusParser->getStatusDetailForCommand('Delete');
		if (is_null($status))
			throw new Exception('Status could not be found after deletion request');
		
		if ($status != 'Success')
			throw new Exception('Delete failed with status ['.$status.'] and error ['.$statusDetail.']');
			
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanYouTubeDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanYouTubeDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanYouTubeDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanYouTubeDistributionJobProviderData");
		
		$this->handleUpdate($data, $data->distributionProfile, $data->providerData);
		
		return false;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseUpdate::closeUpdate()
	 */
	public function closeUpdate(BorhanDistributionUpdateJobData $data)
	{
		$statusXml = $this->fetchStatusXml($data, $data->distributionProfile, $data->providerData);

		if ($statusXml === false) // no status yet
			return false;
			
		$statusParser = new YouTubeDistributionLegacyStatusParser($statusXml);
		$status = $statusParser->getStatusForCommand('Update');
		$statusDetail = $statusParser->getStatusDetailForCommand('Update');
		if (is_null($status))
			throw new Exception('Status could not be found after distribution update');
		
		if ($status != 'Success')
			throw new Exception('Update failed with status ['.$status.'] and error ['.$statusDetail.']');
			
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineReport::fetchReport()
	 */
	public function fetchReport(BorhanDistributionFetchReportJobData $data)
	{
		return false;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanYouTubeDistributionProfile $distributionProfile
	 * @param BorhanYouTubeDistributionJobProviderData $providerData
	 */
	protected function handleSubmit(BorhanDistributionJobData $data, BorhanYouTubeDistributionProfile $distributionProfile, BorhanYouTubeDistributionJobProviderData $providerData)
	{
		$entryId = $data->entryDistribution->entryId;
		$entry = $this->getEntry($data->entryDistribution->partnerId, $entryId);

		$videoFilePath = $providerData->videoAssetFilePath;
		if (!$videoFilePath)
			throw new BorhanDistributionException('No video asset to distribute, the job will fail');

		if (!file_exists($videoFilePath))
			throw new BorhanDistributionException('The file ['.$videoFilePath.'] was not found (probably not synced yet), the job will retry');
			
		$thumbnailFilePath = $providerData->thumbAssetFilePath;
		
		$feed = new YouTubeDistributionLegacyFeedHelper(self::FEED_TEMPLATE, $distributionProfile, $providerData);
		$feed->setAction('Insert');
		$feed->setMetadataFromEntry();
		$newPlaylists = $feed->setPlaylists($providerData->currentPlaylists);
		$feed->setContentUrl('file://' . pathinfo($videoFilePath, PATHINFO_BASENAME));
		if (file_exists($thumbnailFilePath))
			$feed->setThumbnailUrl('file://' . pathinfo($thumbnailFilePath, PATHINFO_BASENAME));
		$feed->setAdParams();
			
		$sftpManager = $this->getSFTPManager($distributionProfile);
		
		$feed->sendFeed($sftpManager);
		$data->sentData = $feed->getXml();
		$data->results = 'none'; // otherwise kContentDistributionFlowManager won't save sentData
		
		// upload the video
		$videoSFTPPath = $feed->getDirectoryName() . '/' . pathinfo($videoFilePath, PATHINFO_BASENAME);
		$sftpManager->putFile($videoSFTPPath, $videoFilePath);
		
		// upload the thumbnail if exists
		if (file_exists($thumbnailFilePath))
		{
			$thumbnailSFTPPath = $feed->getDirectoryName() . '/' . pathinfo($thumbnailFilePath, PATHINFO_BASENAME);
			$sftpManager->putFile($thumbnailSFTPPath, $thumbnailFilePath);
		}
		
		$feed->setDeliveryComplete($sftpManager);
		
		$providerData->sftpDirectory = $feed->getDirectoryName();
		$providerData->sftpMetadataFilename = $feed->getMetadataTempFileName();
		$providerData->currentPlaylists = $newPlaylists;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanYouTubeDistributionProfile $distributionProfile
	 * @param BorhanYouTubeDistributionJobProviderData $providerData
	 */
	protected function handleDelete(BorhanDistributionJobData $data, BorhanYouTubeDistributionProfile $distributionProfile, BorhanYouTubeDistributionJobProviderData $providerData)
	{
		$feed = new YouTubeDistributionLegacyFeedHelper(self::FEED_TEMPLATE, $distributionProfile, $providerData);
		$feed->setAction('Delete');
		$feed->setVideoId($data->remoteId);
		$feed->setDistributionRestrictionRule(""); //to update <yt:distribution_restriction> field 
		
		$sftpManager = $this->getSFTPManager($distributionProfile);
		
		$feed->sendFeed($sftpManager);
		$data->sentData = $feed->getXml();
		$data->results = 'none'; // otherwise kContentDistributionFlowManager won't save sentData
		$feed->setDeliveryComplete($sftpManager);
		
		$providerData->sftpDirectory = $feed->getDirectoryName();
		$providerData->sftpMetadataFilename = $feed->getMetadataTempFileName();
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanYouTubeDistributionProfile $distributionProfile
	 * @param BorhanYouTubeDistributionJobProviderData $providerData
	 */
	protected function handleUpdate(BorhanDistributionJobData $data, BorhanYouTubeDistributionProfile $distributionProfile, BorhanYouTubeDistributionJobProviderData $providerData)
	{
		$entryId = $data->entryDistribution->entryId;
		$entry = $this->getEntry($data->entryDistribution->partnerId, $entryId);

		$feed = new YouTubeDistributionLegacyFeedHelper(self::FEED_TEMPLATE, $distributionProfile, $providerData);
		$feed->setAction('Update');
		$feed->setVideoId($data->remoteId);
		$feed->setMetadataFromEntry();
		$newPlaylists = $feed->setPlaylists($providerData->currentPlaylists);
		$feed->setAdParams();
		
		$thumbnailFilePath = $providerData->thumbAssetFilePath;
		if (file_exists($thumbnailFilePath))
			$feed->setThumbnailUrl('file://' . pathinfo($thumbnailFilePath, PATHINFO_BASENAME));
		
		$sftpManager = $this->getSFTPManager($distributionProfile);
			
		if (file_exists($thumbnailFilePath))
		{
			$thumbnailSFTPPath = $feed->getDirectoryName() . '/' . pathinfo($thumbnailFilePath, PATHINFO_BASENAME);
			$sftpManager->putFile($thumbnailSFTPPath, $thumbnailFilePath);
		}
		
		$feed->sendFeed($sftpManager);
		$data->sentData = $feed->getXml();
		$data->results = 'none'; // otherwise kContentDistributionFlowManager won't save sentData
		$feed->setDeliveryComplete($sftpManager);
		
		$providerData->sftpDirectory = $feed->getDirectoryName();
		$providerData->sftpMetadataFilename = $feed->getMetadataTempFileName();
		$providerData->currentPlaylists = $newPlaylists;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanYouTubeDistributionProfile $distributionProfile
	 * @param BorhanYouTubeDistributionJobProviderData $providerData
	 * @return Status XML or FALSE when status is not available yet
	 */
	protected function fetchStatusXml(BorhanDistributionJobData $data, BorhanYouTubeDistributionProfile $distributionProfile, BorhanYouTubeDistributionJobProviderData $providerData)
	{
		$statusFilePath = $providerData->sftpDirectory . '/' . 'status-' . $providerData->sftpMetadataFilename;
		$sftpManager = $this->getSFTPManager($distributionProfile);
		$statusXml = null;
		try
		{
			BorhanLog::info('Trying to get the following status file: ['.$statusFilePath.']');
			$statusXml = $sftpManager->getFile($statusFilePath);
		}
		catch(kFileTransferMgrException $ex) // file is still missing
		{
			BorhanLog::info('File doesn\'t exist yet, retry later');
			return false;
		}

		BorhanLog::info("Status file was found [$statusXml]");

		$data->results = $statusXml;
		return $statusXml;
	}

	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanYouTubeDistributionProfile $distributionProfile
	 * @param BorhanYouTubeDistributionJobProviderData $providerData
	 * @return string Status XML or FALSE when status is not available yet
	 */
	protected function fetchBatchStatus(BorhanDistributionJobData $data, BorhanYouTubeDistributionProfile $distributionProfile, BorhanYouTubeDistributionJobProviderData $providerData)
	{
		$statusFilePath = $providerData->sftpDirectory . '/internal_failure-status.xml';
		$sftpManager = $this->getSFTPManager($distributionProfile);
		$statusXml = null;
		try
		{
			BorhanLog::info('Trying to get the following status file: ['.$statusFilePath.']');
			$statusXml = $sftpManager->getFile($statusFilePath);
			BorhanLog::info("Status file was found [$statusXml]");
			return $statusXml;
		}
		catch(kFileTransferMgrException $ex) // file is still missing
		{
			BorhanLog::info('File doesn\'t exist yet, so no internal failure was found till now');
			return false;
		}
	}
	
	/**
	 * 
	 * @param BorhanYouTubeDistributionProfile $distributionProfile
	 * @return sftpMgr
	 */
	protected function getSFTPManager(BorhanYouTubeDistributionProfile $distributionProfile)
	{
		if (!is_null($this->_sftpManager))
			return $this->_sftpManager;

		$serverUrl = $distributionProfile->sftpHost;
		$loginName = $distributionProfile->sftpLogin;
		$publicKeyFile = $this->getFileLocationForSFTPKey($distributionProfile->id, $distributionProfile->sftpPublicKey, 'publickey');
		$privateKeyFile = $this->getFileLocationForSFTPKey($distributionProfile->id, $distributionProfile->sftpPrivateKey, 'privatekey');
		$port = 22;
		if ($distributionProfile->sftpPort)
			$port = $distributionProfile->sftpPort;
		$engineOptions = isset(KBatchBase::$taskConfig->engineOptions) ? KBatchBase::$taskConfig->engineOptions->toArray() : array();
		$sftpManager = kFileTransferMgr::getInstance(kFileTransferMgrType::SFTP, $engineOptions);
		$sftpManager->loginPubKey($serverUrl, $loginName, $publicKeyFile, $privateKeyFile, null, $port);
		$this->_sftpManager = $sftpManager;
		return $this->_sftpManager;
	}
	
	/*
	 * Lazy saving of the key to a temporary path, the key will exist in this location until the temp files are purged 
	 */
	protected function getFileLocationForSFTPKey($distributionProfileId, $keyContent, $fileName) 
	{
		$tempDirectory = $this->getTempDirectoryForProfile($distributionProfileId);
		$fileLocation = $tempDirectory . $fileName;
		if (!file_exists($fileLocation) || (file_get_contents($fileLocation) !== $keyContent))
		{
			file_put_contents($fileLocation, $keyContent);
			chmod($fileLocation, 0600);
		}
		
		return $fileLocation;
	}
	
	/*
	 * Creates and return the temp directory used for this distribution profile 
	 */
	protected function getTempDirectoryForProfile($distributionProfileId)
	{
		$metadataTempFilePath = $this->tempDirectory . '/' . self::TEMP_DIRECTORY . '/'  . $distributionProfileId . '/';
		if (!file_exists($metadataTempFilePath))
			mkdir($metadataTempFilePath, 0777, true);
		return $metadataTempFilePath;
	}
}
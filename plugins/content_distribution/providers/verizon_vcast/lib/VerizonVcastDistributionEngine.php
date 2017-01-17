<?php
/**
 * @package plugins.verizonVcastDistribution
 * @subpackage lib
 */
class VerizonVcastDistributionEngine extends DistributionEngine implements 
	IDistributionEngineSubmit,
	IDistributionEngineCloseSubmit,
	IDistributionEngineUpdate,
	IDistributionEngineCloseUpdate,
	IDistributionEngineDelete,
	IDistributionEngineCloseDelete
{
	const VERIZON_STATUS_PUBLISHED = 'PUBLISHED';
	const VERIZON_STATUS_PENDING = 'PENDING';
	
	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseSubmit::closeSubmit()
	 */
	public function closeSubmit(BorhanDistributionSubmitJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
		
		// verizon didn't approve that this logic does work, for now just mark every submited xml as successful
		return true;
		
		$publishState = $this->fetchStatus($data);
		switch($publishState)
		{
			case self::VERIZON_STATUS_PUBLISHED:
				return true;
			case self::VERIZON_STATUS_PENDING:
				return false;
			default:
				throw new Exception("Unknown status [$publishState]");
		}
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
	 * (non-PHPdoc)
	 * @see IDistributionEngineCloseUpdate::closeUpdate()
	 */
	public function closeUpdate(BorhanDistributionUpdateJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IDistributionEngineDelete::delete()
	 */
	public function delete(BorhanDistributionDeleteJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IDistributionEngineCloseDelete::closeDelete()
	 */
	public function closeDelete(BorhanDistributionDeleteJobData $data)
	{
		$this->validateJobDataObjectTypes($data);
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @throws Exception
	 */
	protected function validateJobDataObjectTypes(BorhanDistributionJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanVerizonVcastDistributionProfile))
			throw new Exception("Distribution profile must be of type BorhanVerizonVcastDistributionProfile");
	
		if(!$data->providerData || !($data->providerData instanceof BorhanVerizonVcastDistributionJobProviderData))
			throw new Exception("Provider data must be of type BorhanVerizonVcastDistributionJobProviderData");
	}
	
	/**
	 * @param string $path
	 * @param BorhanDistributionJobData $data
	 * @param BorhanVerizonDistributionProfile $distributionProfile
	 * @param BorhanVerizonDistributionJobProviderData $providerData
	 */
	public function handleSubmit(BorhanDistributionJobData $data, BorhanVerizonVcastDistributionProfile $distributionProfile, BorhanVerizonVcastDistributionJobProviderData $providerData)
	{
		$fileName = $data->entryDistribution->entryId . '_' . date('Y-m-d_H-i-s') . '.xml';
		BorhanLog::info('Sending file '. $fileName);
		
		$ftpManager = $this->getFTPManager($distributionProfile);
		$tmpFile = tmpfile();
		if ($tmpFile === false)
			throw new Exception('Failed to create tmp file');
		fwrite($tmpFile, $providerData->xml);
		rewind($tmpFile);
		$res = ftp_fput($ftpManager->getConnection(), $fileName, $tmpFile, FTP_ASCII);
		fclose($tmpFile);
		
		if ($res === false)
			throw new Exception('Failed to upload tmp file to ftp');
			
		$data->remoteId = $fileName;
		$data->sentData = $providerData->xml;
	}
	
	/**
	 * 
	 * @param BorhanVerizonVcastDistributionProfile $distributionProfile
	 * @return ftpMgr
	 */
	protected function getFTPManager(BorhanVerizonVcastDistributionProfile $distributionProfile)
	{
		$host = $distributionProfile->ftpHost;
		$login = $distributionProfile->ftpLogin;
		$pass = $distributionProfile->ftpPass;
		$engineOptions = isset(KBatchBase::$taskConfig->engineOptions) ? KBatchBase::$taskConfig->engineOptions->toArray() : array();
		$ftpManager = kFileTransferMgr::getInstance(kFileTransferMgrType::FTP, $engineOptions);
		$ftpManager->login($host, $login, $pass);
		return $ftpManager;
	}
	
	/**
	 * @param BorhanDistributionSubmitJobData $data
	 * @return string status
	 */
	protected function fetchStatus(BorhanDistributionJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanVerizonVcastDistributionProfile))
			return BorhanLog::err("Distribution profile must be of type BorhanVerizonVcastDistributionProfile");
	
		$fileArray = $this->fetchFilesList($data->distributionProfile);
		
		for	($i=0; $i<count($fileArray); $i++)
		{
			if (preg_match ( "/{$data->remoteId}.rcvd/" , $fileArray[$i] , $matches))
			{
				return self::VERIZON_STATUS_PUBLISHED;
			}
			else if (preg_match ( "/{$data->remoteId}.*.err/" , $fileArray[$i] , $matches))
			{
				$res = preg_split ('/\./', $matches[0]);
				return $res[1];			
			}
		}

		return self::VERIZON_STATUS_PENDING;
	}

	/**
	 * @param BorhanVerizonDistributionProfile $distributionProfile
	 */
	protected function fetchFilesList(BorhanVerizonVcastDistributionProfile $distributionProfile)
	{
		$host = $distributionProfile->ftpHost;
		$login = $distributionProfile->ftpLogin;
		$pass = $distributionProfile->ftpPass;
		
		$engineOptions = isset(KBatchBase::$taskConfig->engineOptions) ? KBatchBase::$taskConfig->engineOptions->toArray() : array();
		$fileTransferMgr = kFileTransferMgr::getInstance(kFileTransferMgrType::FTP, $engineOptions);
		if(!$fileTransferMgr)
			throw new Exception("FTP manager not loaded");
			
		$fileTransferMgr->login($host, $host, $pass);
		return $fileTransferMgr->listDir('/');
	}

}
<?php
/**
 * @package plugins.cielo24
 * @subpackage Scheduler
 */
class KCielo24IntegrationEngine implements KIntegrationCloserEngine
{
	private $baseEndpointUrl = null;
	private $clientHelper = null;
	
	const GET_URL_FILE_NAME = "borhanFile";
	
	/* (non-PHPdoc)
	 * @see KIntegrationCloserEngine::dispatch()
	 */
	public function dispatch(BorhanBatchJob $job, BorhanIntegrationJobData &$data)
	{
		return $this->doDispatch($job, $data, $data->providerData);
	}
	
	/* (non-PHPdoc)
	 * @see KIntegrationCloserEngine::close()
	 */
	public function close(BorhanBatchJob $job, BorhanIntegrationJobData &$data)
	{
		return $this->doClose($job, $data, $data->providerData);
	}
	
	protected function doDispatch(BorhanBatchJob $job, BorhanIntegrationJobData &$data, BorhanCielo24JobProviderData $providerData)
	{
		$entryId = $providerData->entryId;
		$flavorAssetId = $providerData->flavorAssetId;
		$spokenLanguage = $providerData->spokenLanguage;
		$priority = $providerData->priority;
		$fidelity = $providerData->fidelity;
	
		$formatsString = $providerData->captionAssetFormats;
		$formatsArray = explode(',', $formatsString);
	
		$shouldReplaceRemoteMedia = $providerData->replaceMediaContent;
		$callBackUrl = $data->callbackNotificationUrl;
		BorhanLog::debug('callback is - ' . $callBackUrl);	
	
		$this->clientHelper = Cielo24Plugin::getClientHelper($providerData->username, $providerData->password, $providerData->baseUrl);
		
		//setting a pre-defined name to prevent the flavor-url to contain chars that will break the curl url syntax
		$nameOptions = new BorhanFlavorAssetUrlOptions();
		$nameOptions->fileName = self::GET_URL_FILE_NAME;	
		$flavorUrl = KBatchBase::$kClient->flavorAsset->getUrl($flavorAssetId, null, null, $nameOptions);

		$languageName = $this->clientHelper->getLanguageConstantName($spokenLanguage);
		$jobNameForSearch = $entryId . "_$languageName";

		if($shouldReplaceRemoteMedia == true)
		{
			$jobIds = $this->clientHelper->getRemoteJobIdByName($entryId, $jobNameForSearch . "*", true);
			foreach($jobIds as $remoteJobId)
				$this->clientHelper->deleteRemoteFile($remoteJobId);
		}

		$jobId = $job->id;
		$jobNameForUpload = $jobNameForSearch . "_$jobId";

		$uploadSuccess = $this->clientHelper->uploadMedia($flavorUrl, $entryId, $callBackUrl, $spokenLanguage, $priority, $fidelity, $jobNameForUpload);
		if(!$uploadSuccess)
			throw new Exception("upload failed");
	
		return false;
	}
	
	protected function doClose(BorhanBatchJob $job, BorhanIntegrationJobData &$data, BorhanCielo24JobProviderData $providerData)
	{
		return false;
	}
}

<?php
/**
 * @package plugins.unicornDistribution
 * @subpackage lib
 */
class UnicornDistributionEngine extends DistributionEngine implements IDistributionEngineUpdate, IDistributionEngineSubmit, IDistributionEngineDelete, IDistributionEngineCloseSubmit, IDistributionEngineCloseUpdate, IDistributionEngineCloseDelete
{
	const FAR_FUTURE = 933120000; // 60s * 60m * 24h * 30d * 12m * 30y
	
	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanUnicornDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanUnicornDistributionProfile");
		
		if(!$data->providerData || !($data->providerData instanceof BorhanUnicornDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUnicornDistributionJobProviderData");
		
		$this->handleSubmit($data, $data->distributionProfile, $data->providerData);
		
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanUnicornDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanUnicornDistributionProfile");
		
		if(!$data->providerData || !($data->providerData instanceof BorhanUnicornDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUnicornDistributionJobProviderData");
		
		return $this->handleSubmit($data, $data->distributionProfile, $data->providerData);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineDelete::delete()
	 */
	public function delete(BorhanDistributionDeleteJobData $data)
	{
		if(!$data->distributionProfile || !($data->distributionProfile instanceof BorhanUnicornDistributionProfile))
			BorhanLog::err("Distribution profile must be of type BorhanUnicornDistributionProfile");
		
		if(!$data->providerData || !($data->providerData instanceof BorhanUnicornDistributionJobProviderData))
			BorhanLog::err("Provider data must be of type BorhanUnicornDistributionJobProviderData");
		
		$this->handleDelete($data, $data->distributionProfile, $data->providerData);
		
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseSubmit::closeSubmit()
	 */
	public function closeSubmit(BorhanDistributionSubmitJobData $data)
	{
		// will be closed by the callback notification
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseUpdate::closeUpdate()
	 */
	public function closeUpdate(BorhanDistributionUpdateJobData $data)
	{
		// will be closed by the callback notification
		return false;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseDelete::closeDelete()
	 */
	public function closeDelete(BorhanDistributionDeleteJobData $data)
	{
		// will be closed by the callback notification
		return false;
	}
	
	protected function getNotificationUrl(BorhanUnicornDistributionJobProviderData $providerData)
	{
		$job = KJobHandlerWorker::getCurrentJob();
		$serviceUrl = trim($providerData->notificationBaseUrl, '/');
		return "$serviceUrl/api_v3/index.php/service/unicornDistribution_unicorn/action/notify/partnerId/{$job->partnerId}/id/{$job->id}";
	}
	
	/**
	 * @param int $partnerId
	 * @param string $entryId
	 * @param string $assetIds comma seperated
	 * @return array<BorhanCaptionAsset>
	 */
	protected function getCaptionAssets($partnerId, $entryId, $assetIds)
	{
		KBatchBase::impersonate($partnerId);
		$filter = new BorhanCaptionAssetFilter();
		$filter->entryIdEqual = $entryId;
		$filter->idIn = $assetIds;
		
		$captionPlugin = BorhanCaptionClientPlugin::get(KBatchBase::$kClient);
		$assetsList = $captionPlugin->captionAsset->listAction($filter);
		KBatchBase::unimpersonate();
		
		return $assetsList->objects;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanUnicornDistributionProfile $distributionProfile
	 * @param BorhanUnicornDistributionJobProviderData $providerData
	 * @return string
	 */
	protected function buildXml(BorhanDistributionJobData $data, BorhanUnicornDistributionProfile $distributionProfile, BorhanUnicornDistributionJobProviderData $providerData)
	{
		$entryDistribution = $data->entryDistribution;
		/* @var $entryDistribution BorhanEntryDistribution */
		
		$flavorAssetIds = explode(',', $entryDistribution->flavorAssetIds);
		$flavorAssetId = reset($flavorAssetIds);
		$downloadURL = $this->getFlavorAssetUrl($flavorAssetId);
		
		$xml = new SimpleXMLElement('<APIIngestRequest/>');
		$xml->addChild('UserName', $distributionProfile->username);
		$xml->addChild('Password', $distributionProfile->password);
		$xml->addChild('DomainName', $distributionProfile->domainName);
		
		$avItemXml = $xml->addChild('AVItem');
		$avItemXml->addChild('CatalogGUID', $providerData->catalogGuid);
		$avItemXml->addChild('ForeignKey', $entryDistribution->entryId);
		$avItemXml->addChild('IngestItemType', 'Video');
		
		$ingestInfoXml = $avItemXml->addChild('IngestInfo');
		$ingestInfoXml->addChild('DownloadURL', $downloadURL);
		
		$avItemXml->addChild('Title', $providerData->title);
		
		if($entryDistribution->assetIds)
		{
			$captionsXml = $avItemXml->addChild('Captions');
			
			$captions = $this->getCaptionAssets($entryDistribution->partnerId, $entryDistribution->entryId, $entryDistribution->assetIds);
			foreach($captions as $caption)
			{
				/* @var $caption BorhanCaptionAsset */
				$captionXml = $captionsXml->addChild('Caption');
				$captionXml->addChild('ForeignKey', $caption->id);
				
				$ingestInfoXml = $captionXml->addChild('IngestInfo');
				$ingestInfoXml->addChild('DownloadURL', $this->getFlavorAssetUrl($caption->id));
				
				$captionXml->addChild('Language', $caption->languageCode);
			}
		}
		
		$publicationRulesXml = $avItemXml->addChild('PublicationRules');
		$publicationRuleXml = $publicationRulesXml->addChild('PublicationRule');
		
		$format = 'Y-m-d\TH:i:s\Z'; // e.g. 2007-03-01T13:00:00Z
		$publicationRuleXml->addChild('ChannelGUID', $distributionProfile->channelGuid);
		$publicationRuleXml->addChild('StartDate', date($format, $data->entryDistribution->sunrise));
		
		if($data instanceof BorhanDistributionDeleteJobData)
		{
			$publicationRuleXml->addChild('EndDate', date($format, time()));
		}
		elseif($data->entryDistribution->sunset)
		{
			$publicationRuleXml->addChild('EndDate', date($format, $data->entryDistribution->sunset));
		}
		else
		{
			$publicationRuleXml->addChild('EndDate', date($format, time() + self::FAR_FUTURE));
		}
		
		$xml->addChild('NotificationURL', $this->getNotificationUrl($providerData));
		$xml->addChild('NotificationRequestMethod', 'GET');
		
		return $xml->asXML();
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanUnicornDistributionProfile $distributionProfile
	 * @param BorhanUnicornDistributionJobProviderData $providerData
	 */
	protected function handleSubmit(BorhanDistributionJobData $data, BorhanUnicornDistributionProfile $distributionProfile, BorhanUnicornDistributionJobProviderData $providerData)
	{
		$xml = $this->buildXml($data, $distributionProfile, $providerData);
		$data->sentData = $xml;
		$remoteId = $this->send($distributionProfile, $xml);
		if($remoteId)
		{
			BorhanLog::info("Remote ID [$remoteId]");
			$data->remoteId = $remoteId;
		}
		
		return !$providerData->mediaChanged;
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanUnicornDistributionProfile $distributionProfile
	 * @param BorhanUnicornDistributionJobProviderData $providerData
	 */
	protected function handleDelete(BorhanDistributionJobData $data, BorhanUnicornDistributionProfile $distributionProfile, BorhanUnicornDistributionJobProviderData $providerData)
	{
		$xml = $this->buildXml($data, $distributionProfile, $providerData);
		$data->sentData = $xml;
		$this->send($distributionProfile, $xml);
	}
	
	/**
	 * @param BorhanDistributionJobData $data
	 * @param BorhanUnicornDistributionProfile $distributionProfile
	 * @param BorhanUnicornDistributionJobProviderData $providerData
	 */
	protected function send(BorhanUnicornDistributionProfile $distributionProfile, $xml)
	{
		$ch = curl_init($distributionProfile->apiHostUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
		$response = curl_exec($ch);
		
		
		if(!$response)
		{
			$curlError = curl_error($ch);
			$curlErrorNumber = curl_errno($ch);
			curl_close($ch);
			throw new BorhanDispatcherException("HTTP request failed: $curlError", $curlErrorNumber);
		}
		curl_close($ch);
		BorhanLog::info("Response [$response]");
	
		$matches = null;
		if(preg_match_all('/HTTP\/?[\d.]{0,3} ([\d]{3}) ([^\n\r]+)/', $response, $matches))
		{
			foreach($matches[0] as $index => $match)
			{
				$code = intval($matches[1][$index]);
				$message = $matches[2][$index];
			
				if($code == KCurlHeaderResponse::HTTP_STATUS_CONTINUE)
				{
					continue;
				}
				
				if($code != KCurlHeaderResponse::HTTP_STATUS_OK)
				{
					throw new Exception("HTTP response code [$code] error: $message", $code);
				}
				
				if(preg_match('/^MediaItemGuid: (.+)$/', $message, $matches))
				{
					return $matches[1];
				}
				
				return null;
			}
		}

		throw new BorhanDistributionException("Unexpected HTTP response");
	}
}
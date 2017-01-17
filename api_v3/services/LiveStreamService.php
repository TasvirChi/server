<?php

/**
 * Live Stream service lets you manage live stream entries
 *
 * @service liveStream
 * @package api
 * @subpackage services
 */
class LiveStreamService extends BorhanLiveEntryService
{
	const ISLIVE_ACTION_CACHE_EXPIRY_WHEN_NOT_LIVE = 10;
	const ISLIVE_ACTION_CACHE_EXPIRY_WHEN_LIVE = 30;
	const ISLIVE_ACTION_NON_BORHAN_LIVE_CONDITIONAL_CACHE_EXPIRY = 10;
	const HLS_LIVE_STREAM_CONTENT_TYPE = 'application/vnd.apple.mpegurl';

	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		if($this->getPartnerId() > 0 && !PermissionPeer::isValidForPartner(PermissionName::FEATURE_LIVE_STREAM, $this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
	}
	
	
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'isLive') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}
	
	/**
	 * Adds new live stream entry.
	 * The entry will be queued for provision.
	 * 
	 * @action add
	 * @param BorhanLiveStreamEntry $liveStreamEntry Live stream entry metadata  
	 * @param BorhanSourceType $sourceType  Live stream source type
	 * @return BorhanLiveStreamEntry The new live stream entry
	 * 
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 */
	function addAction(BorhanLiveStreamEntry $liveStreamEntry, $sourceType = null)
	{
		if($sourceType) {
			$liveStreamEntry->sourceType = $sourceType;	
		}
		elseif(is_null($liveStreamEntry->sourceType)) {
			// default sourceType is AKAMAI_LIVE
			$liveStreamEntry->sourceType = kPluginableEnumsManager::coreToApi('EntrySourceType', $this->getPartner()->getDefaultLiveStreamEntrySourceType());
		}
	
		$dbEntry = $this->prepareEntryForInsert($liveStreamEntry);
		$dbEntry->save();
		
		$te = new TrackEntry();
		$te->setEntryId($dbEntry->getId());
		$te->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$te->setDescription(__METHOD__ . ":" . __LINE__ . "::" . $dbEntry->getSource());
		TrackEntry::addTrackEntry($te);
		
		//If a jobData can be created for entry sourceType, add provision job. Otherwise, just save the entry.
		$jobData = kProvisionJobData::getInstance($dbEntry->getSource());
		if ($jobData)
		{
			/* @var $data kProvisionJobData */
			$jobData->populateFromPartner($dbEntry->getPartner());
			$jobData->populateFromEntry($dbEntry);
			kJobsManager::addProvisionProvideJob(null, $dbEntry, $jobData);
		}
		else
		{
			$dbEntry->setStatus(entryStatus::READY);
			$dbEntry->save();
		
			$liveAssets = assetPeer::retrieveByEntryId($dbEntry->getId(),array(assetType::LIVE));
			foreach ($liveAssets as $liveAsset){
				/* @var $liveAsset liveAsset */
				$liveAsset->setStatus(asset::ASSET_STATUS_READY);
				$liveAsset->save();
			}
		}
		
		myNotificationMgr::createNotification( kNotificationJobData::NOTIFICATION_TYPE_ENTRY_ADD, $dbEntry, $this->getPartnerId(), null, null, null, $dbEntry->getId());

		$liveStreamEntry->fromObject($dbEntry, $this->getResponseProfile());
		return $liveStreamEntry;
	}

	protected function prepareEntryForInsert(BorhanBaseEntry $entry, entry $dbEntry = null)
	{
		$dbEntry = parent::prepareEntryForInsert($entry, $dbEntry);
		/* @var $dbEntry LiveStreamEntry */
				
		if(in_array($entry->sourceType, array(BorhanSourceType::LIVE_STREAM, BorhanSourceType::LIVE_STREAM_ONTEXTDATA_CAPTIONS)))
		{
			if(!$entry->conversionProfileId)
			{
				$partner = $dbEntry->getPartner();
				if($partner)
					$dbEntry->setConversionProfileId($partner->getDefaultLiveConversionProfileId());
			}
				
			$dbEntry->save();
			
			$broadcastUrlManager = kBroadcastUrlManager::getInstance($dbEntry->getPartnerId());
			$broadcastUrlManager->setEntryBroadcastingUrls($dbEntry);
		}
		
		return $dbEntry;
	}
	
	/**
	 * Get live stream entry by ID.
	 * 
	 * @action get
	 * @param string $entryId Live stream entry id
	 * @param int $version Desired version of the data
	 * @return BorhanLiveStreamEntry The requested live stream entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	function getAction($entryId, $version = -1)
	{
		return $this->getEntry($entryId, $version, BorhanEntryType::LIVE_STREAM);
	}
	
	/**
	 * Authenticate live-stream entry against stream token and partner limitations
	 * 
	 * @action authenticate
	 * @param string $entryId Live stream entry id
	 * @param string $token Live stream broadcasting token
	 * @param string $hostname Media server host name
	 * @param BorhanEntryServerNodeType $mediaServerIndex Media server index primary / secondary
	 * @param string $applicationName the application to which entry is being broadcast
	 * @return BorhanLiveStreamEntry The authenticated live stream entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::LIVE_STREAM_INVALID_TOKEN
	 */
	function authenticateAction($entryId, $token, $hostname = null, $mediaServerIndex = null, $applicationName = null)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry || $dbEntry->getType() != entryType::LIVE_STREAM)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
		
		/* @var $dbEntry LiveStreamEntry */
		if ($dbEntry->getStreamPassword() != $token)
			throw new BorhanAPIException(BorhanErrors::LIVE_STREAM_INVALID_TOKEN, $entryId);

		/*
		Patch for autenticate error while performing an immidiate stop/start. Checkup for duplicate streams moved to
		media-server for the moment. 
		if($dbEntry->isStreamAlreadyBroadcasting())
			throw new BorhanAPIException(BorhanErrors::LIVE_STREAM_ALREADY_BROADCASTING, $entryId, $mediaServer->getHostname());
		*/
		
		if($hostname && isset($mediaServerIndex))
			$this->setMediaServerWrapper($dbEntry, $mediaServerIndex, $hostname, BorhanEntryServerNodeStatus::AUTHENTICATED, $applicationName);
		
		// fetch current stream live params
		$liveParamsIds = flavorParamsConversionProfilePeer::getFlavorIdsByProfileId($dbEntry->getConversionProfileId());
		$usedLiveParamsIds = array();
		foreach($liveParamsIds as $liveParamsId)
		{
			$usedLiveParamsIds[$liveParamsId] = array($entryId);
		}
			
		// fetch all live entries that currently are live
		$baseCriteria = BorhanCriteria::create(entryPeer::OM_CLASS);
		$filter = new entryFilter();
		$filter->setIsLive(true);
		$filter->setIdNotIn(array($entryId));
		$filter->setPartnerSearchScope(baseObjectFilter::MATCH_BORHAN_NETWORK_AND_PRIVATE);
		$filter->attachToCriteria($baseCriteria);
		
		$entries = entryPeer::doSelect($baseCriteria);
	
		$maxInputStreams = $this->getPartner()->getMaxLiveStreamInputs();
		if(!$maxInputStreams)
			$maxInputStreams = kConf::get('partner_max_live_stream_inputs', 'local', 10);
		BorhanLog::debug("Max live stream inputs [$maxInputStreams]");
			
		$maxTranscodedStreams = 0;
		if(PermissionPeer::isValidForPartner(PermissionName::FEATURE_BORHAN_LIVE_STREAM_TRANSCODE, $this->getPartnerId()))
		{
			$maxTranscodedStreams = $this->getPartner()->getMaxLiveStreamOutputs();
			if(!$maxTranscodedStreams)
				$maxTranscodedStreams = kConf::get('partner_max_live_stream_outputs', 'local', 10);
		}
		BorhanLog::debug("Max live stream outputs [$maxTranscodedStreams]");
		
		$totalInputStreams = count($entries) + 1;
		if($totalInputStreams > ($maxInputStreams + $maxTranscodedStreams))
		{
			BorhanLog::debug("Live input stream [$totalInputStreams]");
			throw new BorhanAPIException(BorhanErrors::LIVE_STREAM_EXCEEDED_MAX_PASSTHRU, $entryId);
		}
		
		$entryIds = array($entryId);
		foreach($entries as $liveEntry)
		{
			/* @var $liveEntry LiveEntry */
			$entryIds[] = $liveEntry->getId();
			$liveParamsIds = array_map('intval', explode(',', $liveEntry->getFlavorParamsIds()));
			
			foreach($liveParamsIds as $liveParamsId)
			{
				if(isset($usedLiveParamsIds[$liveParamsId]))
				{
					$usedLiveParamsIds[$liveParamsId][] = $liveEntry->getId();
				}
				else
				{
					$usedLiveParamsIds[$liveParamsId] = array($liveEntry->getId());
				}
			}
		}
			
		$liveParams = assetParamsPeer::retrieveByPKs(array_keys($usedLiveParamsIds));
		$passthruEntries = null;
		$transcodedEntries = null;
		foreach($liveParams as $liveParamsItem)
		{
			/* @var $liveParamsItem LiveParams */
			if($liveParamsItem->hasTag(liveParams::TAG_INGEST))
			{
				$passthruEntries = array_intersect(is_array($passthruEntries) ? $passthruEntries : $entryIds, $usedLiveParamsIds[$liveParamsItem->getId()]);
			}
			else
			{
				$transcodedEntries = array_intersect(is_array($transcodedEntries) ? $transcodedEntries : $entryIds, $usedLiveParamsIds[$liveParamsItem->getId()]);
			}
		}
		$passthruEntries = array_diff($passthruEntries, $transcodedEntries);
		
		$passthruEntriesCount = count($passthruEntries);
		$transcodedEntriesCount = count($transcodedEntries);
		
		BorhanLog::debug("Live transcoded entries [$transcodedEntriesCount], max live transcoded streams [$maxTranscodedStreams]");
		if($transcodedEntriesCount > $maxTranscodedStreams)
			throw new BorhanAPIException(BorhanErrors::LIVE_STREAM_EXCEEDED_MAX_TRANSCODED, $entryId);
		
		$maxInputStreams += ($maxTranscodedStreams - $transcodedEntriesCount);
		BorhanLog::debug("Live params inputs [$passthruEntriesCount], max live stream inputs [$maxInputStreams]");
		if($passthruEntriesCount > $maxInputStreams)
			throw new BorhanAPIException(BorhanErrors::LIVE_STREAM_EXCEEDED_MAX_PASSTHRU, $entryId);

		$entry = BorhanEntryFactory::getInstanceByType($dbEntry->getType());
		$entry->fromObject($dbEntry, $this->getResponseProfile());
		return $entry;
	}

	/**
	 * Update live stream entry. Only the properties that were set will be updated.
	 * 
	 * @action update
	 * @param string $entryId Live stream entry id to update
	 * @param BorhanLiveStreamEntry $liveStreamEntry Live stream entry metadata to update
	 * @return BorhanLiveStreamEntry The updated live stream entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry entryId edit
	 */
	function updateAction($entryId, BorhanLiveStreamEntry $liveStreamEntry)
	{
		$this->dumpApiRequest($entryId);
		return $this->updateEntry($entryId, $liveStreamEntry, BorhanEntryType::LIVE_STREAM);
	}

	/**
	 * Delete a live stream entry.
	 *
	 * @action delete
	 * @param string $entryId Live stream entry id to delete
	 * 
 	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
 	 * @validateUser entry entryId edit
	 */
	function deleteAction($entryId)
	{
		$this->deleteEntry($entryId, BorhanEntryType::LIVE_STREAM);
	}
	
	/**
	 * List live stream entries by filter with paging support.
	 * 
	 * @action list
     * @param BorhanLiveStreamEntryFilter $filter live stream entry filter
	 * @param BorhanFilterPager $pager Pager
	 * @return BorhanLiveStreamListResponse Wrapper for array of live stream entries and total count
	 */
	function listAction(BorhanLiveStreamEntryFilter $filter = null, BorhanFilterPager $pager = null)
	{
	    if (!$filter)
			$filter = new BorhanLiveStreamEntryFilter();
			
	    $filter->typeEqual = BorhanEntryType::LIVE_STREAM;
	    list($list, $totalCount) = parent::listEntriesByFilter($filter, $pager);
	    
	    $newList = BorhanLiveStreamEntryArray::fromDbArray($list, $this->getResponseProfile());
		$response = new BorhanLiveStreamListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		return $response;
	}
	


	/**
	 * Update live stream entry thumbnail using a raw jpeg file
	 * 
	 * @action updateOfflineThumbnailJpeg
	 * @param string $entryId live stream entry id
	 * @param file $fileData Jpeg file data
	 * @return BorhanLiveStreamEntry The live stream entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::PERMISSION_DENIED_TO_UPDATE_ENTRY
	 */
	function updateOfflineThumbnailJpegAction($entryId, $fileData)
	{
		return parent::updateThumbnailJpegForEntry($entryId, $fileData, BorhanEntryType::LIVE_STREAM, entry::FILE_SYNC_ENTRY_SUB_TYPE_OFFLINE_THUMB);
	}
	
	/**
	 * Update entry thumbnail using url
	 * 
	 * @action updateOfflineThumbnailFromUrl
	 * @param string $entryId live stream entry id
	 * @param string $url file url
	 * @return BorhanLiveStreamEntry The live stream entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::PERMISSION_DENIED_TO_UPDATE_ENTRY
	 */
	function updateOfflineThumbnailFromUrlAction($entryId, $url)
	{
		return parent::updateThumbnailForEntryFromUrl($entryId, $url, BorhanEntryType::LIVE_STREAM, entry::FILE_SYNC_ENTRY_SUB_TYPE_OFFLINE_THUMB);
	}
	
	/**
	 * Delivering the status of a live stream (on-air/offline) if it is possible
	 * 
	 * @action isLive
	 * @param string $id ID of the live stream
	 * @param BorhanPlaybackProtocol $protocol protocol of the stream to test.
	 * @return bool
	 * 
	 * @throws BorhanErrors::LIVE_STREAM_STATUS_CANNOT_BE_DETERMINED
	 * @throws BorhanErrors::INVALID_ENTRY_ID
	 */
	public function isLiveAction ($id, $protocol)
	{
		if (!kCurrentContext::$ks)
		{
			kEntitlementUtils::initEntitlementEnforcement(null, false);
			$liveStreamEntry = kCurrentContext::initPartnerByEntryId($id);
			if (!$liveStreamEntry || $liveStreamEntry->getStatus() == entryStatus::DELETED)
				throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_ID, $id);

			// enforce entitlement
			$this->setPartnerFilters(kCurrentContext::getCurrentPartnerId());
		}
		else
		{
			$liveStreamEntry = entryPeer::retrieveByPK($id);
		}
		
		if (!$liveStreamEntry || ($liveStreamEntry->getType() != entryType::LIVE_STREAM))
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_ID, $id);

		if (!in_array($liveStreamEntry->getSource(), LiveEntry::$borhanLiveSourceTypes))
			BorhanResponseCacher::setConditionalCacheExpiry(self::ISLIVE_ACTION_NON_BORHAN_LIVE_CONDITIONAL_CACHE_EXPIRY);

		/* @var $liveStreamEntry LiveStreamEntry */
	
		if(in_array($liveStreamEntry->getSource(), array(BorhanSourceType::LIVE_STREAM, BorhanSourceType::LIVE_STREAM_ONTEXTDATA_CAPTIONS)))
		{
			return $this->responseHandlingIsLive($liveStreamEntry->hasMediaServer());
		}
		
		$dpda= new DeliveryProfileDynamicAttributes();
		$dpda->setEntryId($id);
		$dpda->setFormat($protocol);
		
		switch ($protocol)
		{
			case BorhanPlaybackProtocol::HLS:
			case BorhanPlaybackProtocol::APPLE_HTTP:
				$url = $liveStreamEntry->getHlsStreamUrl();
				
				foreach (array(BorhanPlaybackProtocol::HLS, BorhanPlaybackProtocol::APPLE_HTTP) as $hlsProtocol){
					$config = $liveStreamEntry->getLiveStreamConfigurationByProtocol($hlsProtocol, requestUtils::getProtocol());
					if ($config){
						$url = $config->getUrl();
						$protocol = $hlsProtocol;
						break;
					}
				}
				BorhanLog::info('Determining status of live stream URL [' .$url. ']');
				
				$urlManager = DeliveryProfilePeer::getLiveDeliveryProfileByHostName(parse_url($url, PHP_URL_HOST), $dpda);
				if($urlManager)
					return $this->responseHandlingIsLive($urlManager->isLive($url));
				break;
				
			case BorhanPlaybackProtocol::HDS:
			case BorhanPlaybackProtocol::AKAMAI_HDS:
				$config = $liveStreamEntry->getLiveStreamConfigurationByProtocol($protocol, requestUtils::getProtocol());
				if ($config)
				{
					$url = $config->getUrl();
					BorhanLog::info('Determining status of live stream URL [' .$url . ']');
					$urlManager = DeliveryProfilePeer::getLiveDeliveryProfileByHostName(parse_url($url, PHP_URL_HOST), $dpda);
					if($urlManager)
						return $this->responseHandlingIsLive($urlManager->isLive($url));
				}
				break;
		}
		
		throw new BorhanAPIException(BorhanErrors::LIVE_STREAM_STATUS_CANNOT_BE_DETERMINED, $protocol);
	}

	private function responseHandlingIsLive($isLive)
	{
		if (!$isLive){
			BorhanResponseCacher::setExpiry(self::ISLIVE_ACTION_CACHE_EXPIRY_WHEN_NOT_LIVE);
			BorhanResponseCacher::setHeadersCacheExpiry(self::ISLIVE_ACTION_CACHE_EXPIRY_WHEN_NOT_LIVE);
		} else {
			BorhanResponseCacher::setExpiry(self::ISLIVE_ACTION_CACHE_EXPIRY_WHEN_LIVE);
			BorhanResponseCacher::setHeadersCacheExpiry(self::ISLIVE_ACTION_CACHE_EXPIRY_WHEN_LIVE);
		}

		return $isLive;
	}


	/**
	 * Add new pushPublish configuration to entry
	 * 
	 * @action addLiveStreamPushPublishConfiguration
	 * @param string $entryId
	 * @param BorhanPlaybackProtocol $protocol
	 * @param string $url
	 * @param BorhanLiveStreamConfiguration $liveStreamConfiguration
	 * @return BorhanLiveStreamEntry
	 * @throws BorhanErrors::INVALID_ENTRY_ID
	 */
	public function addLiveStreamPushPublishConfigurationAction ($entryId, $protocol, $url = null, BorhanLiveStreamConfiguration $liveStreamConfiguration = null)
	{
		$this->dumpApiRequest($entryId);
		
		$entry = entryPeer::retrieveByPK($entryId);
		if (!$entry || $entry->getType() != entryType::LIVE_STREAM)
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_ID);
		
		//Should not allow usage of both $url and $liveStreamConfiguration
		if ($url && !is_null($liveStreamConfiguration))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN);
			
		/* @var $entry LiveEntry */
		$pushPublishConfigurations = $entry->getPushPublishPlaybackConfigurations();

		$configuration = null;
		if ($url)
		{
			$configuration = new kLiveStreamConfiguration();
			$configuration->setProtocol($protocol);
			$configuration->setUrl($url);
		}
		elseif (!is_null($liveStreamConfiguration))
		{
			$configuration = $liveStreamConfiguration->toInsertableObject();
			$configuration->setProtocol($protocol);
		}
		
		if ($configuration)
		{
			$pushPublishConfigurations[] = $configuration;
			$entry->setPushPublishPlaybackConfigurations($pushPublishConfigurations);
			$entry->save();
		}
		
		$apiEntry = BorhanEntryFactory::getInstanceByType($entry->getType());
		$apiEntry->fromObject($entry, $this->getResponseProfile());
		return $apiEntry;
	}
	
	/**
	 *Remove push publish configuration from entry
	 * 
	 * @action removeLiveStreamPushPublishConfiguration
	 * @param string $entryId
	 * @param BorhanPlaybackProtocol $protocol
	 * @return BorhanLiveStreamEntry
	 * @throws BorhanErrors::INVALID_ENTRY_ID
	 */
	public function removeLiveStreamPushPublishConfigurationAction ($entryId, $protocol)
	{
		$this->dumpApiRequest($entryId);
		
		$entry = entryPeer::retrieveByPK($entryId);
		if (!$entry || $entry->getType() != entryType::LIVE_STREAM)
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_ID);
		
		/* @var $entry LiveEntry */
		$pushPublishConfigurations = $entry->getPushPublishPlaybackConfigurations();
		foreach ($pushPublishConfigurations as $index => $config)
		{
			/* @var $config kLiveStreamConfiguration */
			if ($config->getProtocol() == $protocol)
			{
				unset ($pushPublishConfigurations[$index]);
			}
		}

		$entry->setPushPublishPlaybackConfigurations($pushPublishConfigurations);
		$entry->save();
		
		$apiEntry = BorhanEntryFactory::getInstanceByType($entry->getType());
		$apiEntry->fromObject($entry, $this->getResponseProfile());
		return $apiEntry;
	}
	
	/**
	 * Regenerate new secure token for liveStream
	 * 
	 * @action regenerateStreamToken
	 * @param string $entryId Live stream entry id to regenerate secure token for
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry entryId edit
	 */
	public function regenerateStreamTokenAction($entryId)
	{
		
		$this->dumpApiRequest($entryId);
	
		$liveEntry = entryPeer::retrieveByPK($entryId);
		if (!$liveEntry || $liveEntry->getType() != entryType::LIVE_STREAM)
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_ID);
		
		if (!in_array($liveEntry->getSourceType(), LiveEntry::$borhanLiveSourceTypes))
			throw new BorhanAPIException(BorhanErrors::CANNOT_REGENERATE_STREAM_TOKEN_FOR_EXTERNAL_LIVE_STREAMS, $liveEntry->getSourceType());
		
		$password = sha1(md5(uniqid(rand(), true)));
		$password = substr($password, rand(0, strlen($password) - 8), 8);
		$liveEntry->setStreamPassword($password);
		
		$broadcastUrlManager = kBroadcastUrlManager::getInstance($liveEntry->getPartnerId());
		$broadcastUrlManager->setEntryBroadcastingUrls($liveEntry);
		
		$liveEntry->save();
	
		$entry = BorhanEntryFactory::getInstanceByType($liveEntry->getType());
		$entry->fromObject($liveEntry, $this->getResponseProfile());
		return $entry;
	}
}

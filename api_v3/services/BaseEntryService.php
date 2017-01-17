<?php
/**
 * Base Entry Service
 *
 * @service baseEntry
 * @package api
 * @subpackage services
 */
class BaseEntryService extends BorhanEntryService
{
    /* (non-PHPdoc)
     * @see BorhanEntryService::initService()
     */
    public function initService($serviceId, $serviceName, $actionName)
    {
        parent::initService($serviceId, $serviceName, $actionName);
        $partner = PartnerPeer::retrieveByPK($this->getPartnerId());
        if ($actionName == "anonymousRank" && $partner->getEnabledService(BorhanPermissionName::FEATURE_LIKE))
        {
            throw new BorhanAPIException(BorhanErrors::ACTION_FORBIDDEN, "anonymousRank");
        }
    }
    
	/* (non-PHPdoc)
	 * @see BorhanBaseService::globalPartnerAllowed()
	 */
	protected function globalPartnerAllowed($actionName)
	{
		if($actionName == 'getContextData')
			return true;

		if($actionName == 'getPlaybackContext')
			return true;
		
		return parent::globalPartnerAllowed($actionName);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::borhanNetworkAllowed()
	 */
	protected function borhanNetworkAllowed($actionName)
	{
		if ($actionName === 'get') {
			return true;
		}
		if ($actionName === 'getContextData') {
			return true;
		}
		if($actionName == 'getPlaybackContext'){
			return true;
		}

		return parent::borhanNetworkAllowed($actionName);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::partnerRequired()
	 */
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'flag') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}
	
    /**
     * Generic add entry, should be used when the uploaded entry type is not known.
     *
     * @action add
     * @param BorhanBaseEntry $entry
     * @param BorhanEntryType $type
     * @return BorhanBaseEntry
     * @throws BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED
     */
    function addAction(BorhanBaseEntry $entry, $type = -1)
    {
    	if($type && $type != BorhanEntryType::AUTOMATIC)
    		$entry->type = $type;
    	
    	$dbEntry = parent::add($entry, $entry->conversionProfileId);
    	if($dbEntry->getStatus() != entryStatus::READY)
    	{
	   		$dbEntry->setStatus(entryStatus::NO_CONTENT);
	    	$dbEntry->save();
    	}
    	
		$trackEntry = new TrackEntry();
		$trackEntry->setEntryId($dbEntry->getId());
		$trackEntry->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$trackEntry->setDescription(__METHOD__ . ":" . __LINE__ . "::ENTRY_BASE");
		TrackEntry::addTrackEntry($trackEntry);
		
    	myNotificationMgr::createNotification(kNotificationJobData::NOTIFICATION_TYPE_ENTRY_ADD, $dbEntry, $dbEntry->getPartnerId(), null, null, null, $dbEntry->getId());
    	
	    $entry->fromObject($dbEntry, $this->getResponseProfile());
	    return $entry;
    }
	
    /**
     * Attach content resource to entry in status NO_MEDIA
     *
     * @action addContent
	 * @param string $entryId
     * @param BorhanResource $resource
     * @return BorhanBaseEntry
     * @throws BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED
     * @validateUser entry entryId edit
     */
    function addContentAction($entryId, BorhanResource $resource)
    {
    	$dbEntry = entryPeer::retrieveByPK($entryId);

		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
    	
		
		
		$kResource = $resource->toObject();
    	if($dbEntry->getType() == BorhanEntryType::AUTOMATIC || is_null($dbEntry->getType()))
    		$this->setEntryTypeByResource($dbEntry, $kResource);
		$dbEntry->save();
		
		$resource->validateEntry($dbEntry);
		$this->attachResource($kResource, $dbEntry);
		$resource->entryHandled($dbEntry);
    	
		return $this->getEntry($entryId);
    }

    /**
     * @param kResource $resource
     * @param entry $dbEntry
     * @param asset $asset
     */
    protected function attachResource(kResource $resource, entry $dbEntry, asset $asset = null)
    {
    	$service = null;
    	switch($dbEntry->getType())
    	{
			case entryType::MEDIA_CLIP:
				$service = new MediaService();
    			$service->initService('media', 'media', $this->actionName);
    			break;
				
			case entryType::MIX:
				$service = new MixingService();
    			$service->initService('mixing', 'mixing', $this->actionName);
    			break;
				
			case entryType::PLAYLIST:
				$service = new PlaylistService();
    			$service->initService('playlist', 'playlist', $this->actionName);
    			break;
				
			case entryType::DATA:
				$service = new DataService();
    			$service->initService('data', 'data', $this->actionName);
    			break;
				
			case entryType::LIVE_STREAM:
				$service = new LiveStreamService();
    			$service->initService('liveStream', 'liveStream', $this->actionName);
    			break;
    			
    		default:
    			throw new BorhanAPIException(BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED, $dbEntry->getType());
    	}
    		
    	$service->attachResource($resource, $dbEntry, $asset);
    }
    
    /**
     * @param kResource $resource
     */
    protected function setEntryTypeByResource(entry $dbEntry, kResource $resource)
    {
    	$fullPath = null;
    	switch($resource->getType())
    	{
    		case 'kAssetParamsResourceContainer':
    			return $this->setEntryTypeByResource($dbEntry, $resource->getResource());
    			
			case 'kAssetsParamsResourceContainers':
    			return $this->setEntryTypeByResource($dbEntry, reset($resource->getResources()));
				
			case 'kFileSyncResource':
				$sourceEntry = null;
		    	if($resource->getFileSyncObjectType() == FileSyncObjectType::ENTRY)
		    		$sourceEntry = entryPeer::retrieveByPK($resource->getObjectId());
		    	if($resource->getFileSyncObjectType() == FileSyncObjectType::FLAVOR_ASSET)
		    	{
		    		$sourceAsset = assetPeer::retrieveByPK($resource->getObjectId());
		    		if($sourceAsset)
		    			$sourceEntry = $sourceAsset->getentry();
		    	}
		    	
				if($sourceEntry)
				{
					$dbEntry->setType($sourceEntry->getType());
					$dbEntry->setMediaType($sourceEntry->getMediaType());
				}
				return;
				
			case 'kLocalFileResource':
				$fullPath = $resource->getLocalFilePath();
				break;
				
			case 'kUrlResource':
			case 'kRemoteStorageResource':
				$fullPath = $resource->getUrl();
				break;
				
			default:
				return;
    	}
    	if($fullPath)
    		$this->setEntryTypeByExtension($dbEntry, $fullPath);
    }
    
    protected function setEntryTypeByExtension(entry $dbEntry, $fullPath)
    {
    	$ext = pathinfo($fullPath, PATHINFO_EXTENSION);

    	if(!$ext)
		{
			$ext = myFileUploadService::getExtensionByContentType($fullPath);
			if(!$ext)
				return;
		}
    	
    	$mediaType = myFileUploadService::getMediaTypeFromFileExt($ext);
    	if($mediaType != entry::ENTRY_MEDIA_TYPE_AUTOMATIC)
    	{
			$dbEntry->setType(entryType::MEDIA_CLIP);
			$dbEntry->setMediaType($mediaType);
    	}
    }
    
    /**
     * Generic add entry using an uploaded file, should be used when the uploaded entry type is not known.
     *
     * @action addFromUploadedFile
     * @param BorhanBaseEntry $entry
     * @param string $uploadTokenId
     * @param BorhanEntryType $type
     * @return BorhanBaseEntry
     */
    function addFromUploadedFileAction(BorhanBaseEntry $entry, $uploadTokenId, $type = -1)
    {
		try
	    {
	    	// check that the uploaded file exists
			$entryFullPath = kUploadTokenMgr::getFullPathByUploadTokenId($uploadTokenId);
	    }
	    catch(kCoreException $ex)
	    {
	    	if ($ex->getCode() == kUploadTokenException::UPLOAD_TOKEN_INVALID_STATUS);
	    		throw new BorhanAPIException(BorhanErrors::UPLOAD_TOKEN_INVALID_STATUS_FOR_ADD_ENTRY);
	    		
    		throw $ex;
	    }

		if (!file_exists($entryFullPath))
		{
			// Backward compatability - support case in which the required file exist in the other DC
			kFileUtils::dumpApiRequest ( kDataCenterMgr::getRemoteDcExternalUrlByDcId ( 1 - kDataCenterMgr::getCurrentDcId () ) );
			/*
			$remoteDCHost = kUploadTokenMgr::getRemoteHostForUploadToken($uploadTokenId, kDataCenterMgr::getCurrentDcId());
			if($remoteDCHost)
			{
				kFileUtils::dumpApiRequest($remoteDCHost);
			}
			else
			{
				throw new BorhanAPIException(BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN);
			}
			*/
		}
	    
	    // validate the input object
	    //$entry->validatePropertyMinLength("name", 1);
	    if (!$entry->name)
		    $entry->name = $this->getPartnerId().'_'.time();

	    // first copy all the properties to the db entry, then we'll check for security stuff
	    $dbEntry = $this->duplicateTemplateEntry($entry->conversionProfileId, $entry->templateEntryId);
	    $dbEntry = $entry->toInsertableObject($dbEntry);
	    

	    
	    $dbEntry->setType($type);
	    $dbEntry->setMediaType(entry::ENTRY_MEDIA_TYPE_AUTOMATIC);
	        
	    $this->checkAndSetValidUserInsert($entry, $dbEntry);
	    $this->checkAdminOnlyInsertProperties($entry);
	    $this->validateAccessControlId($entry);
	    $this->validateEntryScheduleDates($entry, $dbEntry);
	    
	    $dbEntry->setPartnerId($this->getPartnerId());
	    $dbEntry->setSubpId($this->getPartnerId() * 100);
	    $dbEntry->setSourceId( $uploadTokenId );
	    $dbEntry->setSourceLink( $entryFullPath );
	    myEntryUtils::setEntryTypeAndMediaTypeFromFile($dbEntry, $entryFullPath);
	    $dbEntry->setDefaultModerationStatus();
		
		// hack due to BCW of version  from BMC
		if (! is_null ( parent::getConversionQualityFromRequest () ))
			$dbEntry->setConversionQuality ( parent::getConversionQualityFromRequest () );
		
	    $dbEntry->save();
	    
	    $kshow = $this->createDummyKShow();
	    $kshowId = $kshow->getId();
	    
	    // setup the needed params for my insert entry helper
	    $paramsArray = array (
		    "entry_media_source" => BorhanSourceType::FILE,
		    "entry_media_type" => $dbEntry->getMediaType(),
		    "entry_full_path" => $entryFullPath,
		    "entry_license" => $dbEntry->getLicenseType(),
		    "entry_credit" => $dbEntry->getCredit(),
		    "entry_source_link" => $dbEntry->getSourceLink(),
		    "entry_tags" => $dbEntry->getTags(),
	    );
			
	    $token = $this->getKsUniqueString();
	    $insert_entry_helper = new myInsertEntryHelper(null , $dbEntry->getKuserId(), $kshowId, $paramsArray);
	    $insert_entry_helper->setPartnerId($this->getPartnerId(), $this->getPartnerId() * 100);
	    $insert_entry_helper->insertEntry($token, $dbEntry->getType(), $dbEntry->getId(), $dbEntry->getName(), $dbEntry->getTags(), $dbEntry);
	    $dbEntry = $insert_entry_helper->getEntry();
	    
	    kUploadTokenMgr::closeUploadTokenById($uploadTokenId);
	    
	    myNotificationMgr::createNotification( kNotificationJobData::NOTIFICATION_TYPE_ENTRY_ADD, $dbEntry);

	    $entry->fromObject($dbEntry, $this->getResponseProfile());
	    return $entry;
    }
    
	/**
	 * Get base entry by ID.
	 *
	 * @action get
	 * @param string $entryId Entry id
	 * @param int $version Desired version of the data
	 * @return BorhanBaseEntry The requested entry
	 */
    function getAction($entryId, $version = -1)
    {
    	return $this->getEntry($entryId, $version);
    }

    /**
     * Get remote storage existing paths for the asset.
     *
     * @action getRemotePaths
     * @param string $entryId
     * @return BorhanRemotePathListResponse
     * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
     * @throws BorhanErrors::ENTRY_NOT_READY
     */
    public function getRemotePathsAction($entryId)
    {
		return $this->getRemotePaths($entryId);
	}
	
	/**
	 * Update base entry. Only the properties that were set will be updated.
	 *
	 * @action update
	 * @param string $entryId Entry id to update
	 * @param BorhanBaseEntry $baseEntry Base entry metadata to update
	 * @param BorhanResource $resource Resource to be used to replace entry content
	 * @return BorhanBaseEntry The updated entry
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry entryId edit
	 */
	function updateAction($entryId, BorhanBaseEntry $baseEntry)
	{
    	$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
	
		$baseEntry = $this->updateEntry($entryId, $baseEntry);
    	return $baseEntry;
	}
	
	/**
	 * Update the content resource associated with the entry.
	 *
	 * @action updateContent
	 * @param string $entryId Entry id to update
	 * @param BorhanResource $resource Resource to be used to replace entry content
	 * @param int $conversionProfileId The conversion profile id to be used on the entry
	 * @param BorhanEntryReplacementOptions $advancedOptions Additional update content options
	 * @return BorhanBaseEntry The updated entry
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry entryId edit
	 */
	function updateContentAction($entryId, BorhanResource $resource, $conversionProfileId = null, $advancedOptions = null)
	{
    	$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
	
	 	if($dbEntry->getType() == BorhanEntryType::AUTOMATIC || is_null($dbEntry->getType()))
        {
        	$kResource = $resource->toObject();
        	$this->setEntryTypeByResource($dbEntry, $kResource);
        	$dbEntry->save();
        }
		
		$baseEntry = new BorhanBaseEntry();
		$baseEntry->fromObject($dbEntry, $this->getResponseProfile());
		
		switch($dbEntry->getType())
    	{
			case entryType::MEDIA_CLIP:
				$service = new MediaService();
    			$service->initService('media', 'media', $this->actionName);
				$service->replaceResource($resource, $dbEntry, $conversionProfileId, $advancedOptions);
		    	$baseEntry->fromObject($dbEntry, $this->getResponseProfile());
    			return $baseEntry;
			case entryType::MIX:
			case entryType::PLAYLIST:
			case entryType::DATA:
			case entryType::LIVE_STREAM:
    		default:
    			// TODO load from plugin manager other entry services such as document
    			throw new BorhanAPIException(BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED, $baseEntry->type);
    	}
    	
    	return $baseEntry;
	}
	
	/**
	 * Get an array of BorhanBaseEntry objects by a comma-separated list of ids.
	 *
	 * @action getByIds
	 * @param string $entryIds Comma separated string of entry ids
	 * @return BorhanBaseEntryArray Array of base entry ids
	 */
	function getByIdsAction($entryIds)
	{
		$entryIdsArray = explode(",", $entryIds);
		
		// remove white spaces
		foreach($entryIdsArray as &$entryId)
			$entryId = trim($entryId);
			
	 	$list = entryPeer::retrieveByPKs($entryIdsArray);
		$newList = array();
		
		$ks = $this->getKs();
		$isAdmin = false;
		if($ks)
			$isAdmin = $ks->isAdmin();
			
	 	foreach($list as $dbEntry)
	 	{
	 		$entry = BorhanEntryFactory::getInstanceByType($dbEntry->getType(), $isAdmin);
		    $entry->fromObject($dbEntry, $this->getResponseProfile());
		    $newList[] = $entry;
	 	}
	 	
	 	return $newList;
	}

	/**
	 * Delete an entry.
	 *
	 * @action delete
	 * @param string $entryId Entry id to delete
	 * @validateUser entry entryId edit ownerOnly
	 */
	function deleteAction($entryId)
	{
		$this->deleteEntry($entryId);
	}
	
	/**
	 * List base entries by filter with paging support.
	 *
	 * @action list
     * @param BorhanBaseEntryFilter $filter Entry filter
	 * @param BorhanFilterPager $pager Pager
	 * @return BorhanBaseEntryListResponse Wrapper for array of base entries and total count
	 */
	function listAction(BorhanBaseEntryFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if(!$filter)
		{
			$filter = new BorhanBaseEntryFilter();
		}
			
		if(!$pager)
		{
			$pager = new BorhanFilterPager();
		}

		$result = $filter->getListResponse($pager, $this->getResponseProfile());
		
		if ($result->totalCount == 1 && 
			count($result->objects) == 1 && 
			$result->objects[0]->status != BorhanEntryStatus::READY)
		{
			// the purpose of this is to solve a case in which a player attempts to play a non-ready entry, 
			// and the request becomes cached for a long time, preventing playback even after the entry
			// becomes ready
			kApiCache::setExpiry(60);
		}
		
		// NOTE: The following is a hack in order to make sure all responses are of type BorhanBaseEntryListResponse.
		//       The reason is that baseentry::list() is not being extended by derived classes.
		$response = new BorhanBaseEntryListResponse();
		$response->objects = $result->objects;
		$response->totalCount = $result->totalCount;
		return $response;
	}
	
	/**
	 * List base entries by filter according to reference id
	 *
	 * @action listByReferenceId
	 * @param string $refId Entry Reference ID
	 * @param BorhanFilterPager $pager Pager
	 * @throws BorhanErrors::MISSING_MANDATORY_PARAMETER
	 * @return BorhanBaseEntryListResponse Wrapper for array of base entries and total count
	 */
	function listByReferenceId($refId, BorhanFilterPager $pager = null)
	{
		if (!$refId)
		{
			//if refId wasn't provided return an error of missing parameter
			throw new BorhanAPIException(BorhanErrors::MISSING_MANDATORY_PARAMETER, $refId);
		}
				
		if (!$pager){
			$pager = new BorhanFilterPager();
		}
		$entryFilter = new entryFilter();
		$entryFilter->setPartnerSearchScope(baseObjectFilter::MATCH_BORHAN_NETWORK_AND_PRIVATE);
		//setting reference ID
		$entryFilter->set('_eq_reference_id', $refId);
		$c = BorhanCriteria::create(entryPeer::OM_CLASS);
		$pager->attachToCriteria($c);
		$entryFilter->attachToCriteria($c);
		$c->add(entryPeer::DISPLAY_IN_SEARCH, mySearchUtils::DISPLAY_IN_SEARCH_SYSTEM, Criteria::NOT_EQUAL);
				
		BorhanCriterion::disableTag(BorhanCriterion::TAG_WIDGET_SESSION);

		if (kEntitlementUtils::getEntitlementEnforcement() && !kCurrentContext::$is_admin_session && entryPeer::getUserContentOnly())
			entryPeer::setFilterResults(true);

		$list = entryPeer::doSelect($c);
		BorhanCriterion::restoreTag(BorhanCriterion::TAG_WIDGET_SESSION);
		
		$totalCount = $c->getRecordsCount();
				
	    $newList = BorhanBaseEntryArray::fromDbArray($list, $this->getResponseProfile());
		$response = new BorhanBaseEntryListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		return $response;
	}
	
	/**
	 * Count base entries by filter.
	 *
	 * @action count
     * @param BorhanBaseEntryFilter $filter Entry filter
	 * @return int
	 */
	function countAction(BorhanBaseEntryFilter $filter = null)
	{
	    return parent::countEntriesByFilter($filter);
	}
	
	/**
	 * Upload a file to Borhan, that can be used to create an entry.
	 *
	 * @action upload
	 * @param file $fileData The file data
	 * @return string Upload token id
	 *
	 * @deprecated use upload.upload or uploadToken.add instead
	 */
	function uploadAction($fileData)
	{
		$ksUnique = $this->getKsUniqueString();
		
		$uniqueId = substr(base_convert(md5(uniqid(rand(), true)), 16, 36), 1, 20);
		
		$ext = pathinfo($fileData["name"], PATHINFO_EXTENSION);
		$token = $ksUnique."_".$uniqueId.".".$ext;
		// filesync ok
		$res = myUploadUtils::uploadFileByToken($fileData, $token, "", null, true);
	
		return $res["token"];
	}
	
	/**
	 * Update entry thumbnail using a raw jpeg file.
	 *
	 * @action updateThumbnailJpeg
	 * @param string $entryId Media entry id
	 * @param file $fileData Jpeg file data
	 * @return BorhanBaseEntry The media entry
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::PERMISSION_DENIED_TO_UPDATE_ENTRY
	 */
	function updateThumbnailJpegAction($entryId, $fileData)
	{
		return parent::updateThumbnailJpegForEntry($entryId, $fileData);
	}
	
	/**
	 * Update entry thumbnail using url.
	 *
	 * @action updateThumbnailFromUrl
	 * @param string $entryId Media entry id
	 * @param string $url file url
	 * @return BorhanBaseEntry The media entry
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::PERMISSION_DENIED_TO_UPDATE_ENTRY
	 */
	function updateThumbnailFromUrlAction($entryId, $url)
	{
		return parent::updateThumbnailForEntryFromUrl($entryId, $url);
	}
	
	/**
	 * Update entry thumbnail from a different entry by a specified time offset (in seconds).
	 *
	 * @action updateThumbnailFromSourceEntry
	 * @param string $entryId Media entry id
	 * @param string $sourceEntryId Media entry id
	 * @param int $timeOffset Time offset (in seconds)
	 * @return BorhanBaseEntry The media entry
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::PERMISSION_DENIED_TO_UPDATE_ENTRY
	 */
	function updateThumbnailFromSourceEntryAction($entryId, $sourceEntryId, $timeOffset)
	{
		return parent::updateThumbnailForEntryFromSourceEntry($entryId, $sourceEntryId, $timeOffset);
	}
	
	/**
	 * Flag inappropriate entry for moderation.
	 *
	 * @action flag
	 * @param string $entryId
	 * @param BorhanModerationFlag $moderationFlag
	 *
 	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	public function flagAction(BorhanModerationFlag $moderationFlag)
	{
		BorhanResponseCacher::disableCache();
		return parent::flagEntry($moderationFlag);
	}
	
	/**
	 * Reject the entry and mark the pending flags (if any) as moderated (this will make the entry non-playable).
	 *
	 * @action reject
	 * @param string $entryId
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	public function rejectAction($entryId)
	{
		parent::rejectEntry($entryId);
	}
	
	/**
	 * Approve the entry and mark the pending flags (if any) as moderated (this will make the entry playable).
	 *
	 * @action approve
	 * @param string $entryId
	 *
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	public function approveAction($entryId)
	{
		parent::approveEntry($entryId);
	}
	
	/**
	 * List all pending flags for the entry.
	 *
	 * @action listFlags
	 * @param string $entryId
	 * @param BorhanFilterPager $pager
	 *
	 * @return BorhanModerationFlagListResponse
	 */
	public function listFlags($entryId, BorhanFilterPager $pager = null)
	{
		return parent::listFlagsForEntry($entryId, $pager);
	}
	
	/**
	 * Anonymously rank an entry, no validation is done on duplicate rankings.
	 *
	 * @action anonymousRank
	 * @param string $entryId
	 * @param int $rank
	 */
	public function anonymousRankAction($entryId, $rank)
	{
		BorhanResponseCacher::disableCache();
		return parent::anonymousRankEntry($entryId, null, $rank);
	}
	
	/**
	 * This action delivers entry-related data, based on the user's context: access control, restriction, playback format and storage information.
	 * @action getContextData
	 * @param string $entryId
	 * @param BorhanEntryContextDataParams $contextDataParams
	 * @return BorhanEntryContextDataResult
	 */
	public function getContextData($entryId, BorhanEntryContextDataParams $contextDataParams)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
		
		if ($dbEntry->getStatus() != entryStatus::READY)
		{
			// the purpose of this is to solve a case in which a player attempts to play a non-ready entry,
			// and the request becomes cached for a long time, preventing playback even after the entry
			// becomes ready
			kApiCache::setExpiry(60);
		}
		
		$asset = null;
		if($contextDataParams->flavorAssetId)
		{
			$asset = assetPeer::retrieveById($contextDataParams->flavorAssetId);
			if(!$asset)
				throw new BorhanAPIException(BorhanErrors::FLAVOR_ASSET_ID_NOT_FOUND, $contextDataParams->flavorAssetId);
		}
			
		$contextDataHelper = new kContextDataHelper($dbEntry, $this->getPartner(), $asset);
		
		if ($dbEntry->getAccessControl() && $dbEntry->getAccessControl()->hasRules())
			$accessControlScope = $dbEntry->getAccessControl()->getScope();
		else
			$accessControlScope = new accessControlScope();
		$contextDataParams->toObject($accessControlScope);
		
		$contextDataHelper->buildContextDataResult($accessControlScope, $contextDataParams->flavorTags, $contextDataParams->streamerType, $contextDataParams->mediaProtocol);
		if($contextDataHelper->getDisableCache())
			BorhanResponseCacher::disableCache();
			
		$result = new BorhanEntryContextDataResult();
		$result->fromObject($contextDataHelper->getContextDataResult());
		$result->flavorAssets = BorhanFlavorAssetArray::fromDbArray($contextDataHelper->getAllowedFlavorAssets());
		$result->msDuration = $contextDataHelper->getMsDuration();
		$result->streamerType = $contextDataHelper->getStreamerType();
		$result->mediaProtocol = $contextDataHelper->getMediaProtocol();
		$result->storageProfilesXML = $contextDataHelper->getStorageProfilesXML();
		$result->isAdmin = $contextDataHelper->getIsAdmin();
		
		$parentEntryId = $dbEntry->getSecurityParentId();
		if ($parentEntryId)
		{
			$dbEntry = $dbEntry->getParentEntry();
			if(!$dbEntry)
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $parentEntryId);
		}
		
		$result->isScheduledNow = $dbEntry->isScheduledNow($contextDataParams->time);
		if (!($result->isScheduledNow) && $this->getKs() ){
			// in case the sview is defined in the ks simulate schedule now true to allow player to pass verification
			if ( $this->getKs()->verifyPrivileges(ks::PRIVILEGE_VIEW, ks::PRIVILEGE_WILDCARD) ||
				$this->getKs()->verifyPrivileges(ks::PRIVILEGE_VIEW, $entryId)) {
				$result->isScheduledNow = true;
			}
		}

        $result->pluginData = new BorhanPluginDataArray();
        $pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanEntryContextDataContributor');
        foreach ($pluginInstances as $pluginInstance)
        {
            $pluginDataCore = $pluginInstance->contributeToEntryContextDataResult($dbEntry, $accessControlScope, $contextDataHelper);
	        if (!is_null($pluginDataCore))
	        {
		        $pluginDataApi = BorhanPluginManager::loadObject('BorhanPluginData', $pluginInstance->getPluginName());
		        $pluginDataApi->fromObject($pluginDataCore);
		        $result->pluginData[get_class($pluginDataApi)] = $pluginDataApi;
	        }
        }

		return $result;
	}
	
	/**
	 * @action export
	 * Action for manually exporting an entry
	 * @param string $entryId
	 * @param int $storageProfileId
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 * @return BorhanBaseEntry The exported entry
	 */
	public function exportAction ( $entryId , $storageProfileId )
	{
	    $dbEntry = entryPeer::retrieveByPK($entryId);
	    if (!$dbEntry)
	    {
	        throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
	    }
	    
	    $dbStorageProfile = StorageProfilePeer::retrieveByPK($storageProfileId);
	    if (!$dbStorageProfile)
	    {
	        throw new BorhanAPIException(BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND, $storageProfileId);
	    }
	    
	    $scope = $dbStorageProfile->getScope();
	    $scope->setEntryId($entryId);
	    if(!$dbStorageProfile->fulfillsRules($scope))
	    {
	    	throw new BorhanAPIException(BorhanErrors::STORAGE_PROFILE_RULES_NOT_FULFILLED, $storageProfileId);
	    }

	    try
	    {
	    	kStorageExporter::exportEntry($dbEntry, $dbStorageProfile);
	    }
	    catch (kCoreException $e)
	    {
	    	if ($e->getCode()==kCoreException::PROFILE_STATUS_DISABLED)
	    	{
 	    		throw new BorhanAPIException(APIErrors::PROFILE_STATUS_DISABLED,$entryId);
	    	}
	    }
	     
	    
	    //TODO: implement export errors
	    
		$entry = BorhanEntryFactory::getInstanceByType($dbEntry->getType());
		$entry->fromObject($dbEntry, $this->getResponseProfile());
	    return $entry;
	    
	}
	
	/**
	 * Index an entry by id.
	 *
	 * @action index
	 * @param string $id
	 * @param bool $shouldUpdate
	 * @return int entry int id
	 */
	function indexAction($id, $shouldUpdate = true)
	{
		if(kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_INDEX_OBJECT_WHEN_ENTITLEMENT_IS_ENABLE);
			
		$entryDb = entryPeer::retrieveByPK($id);
		if (!$entryDb)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $id);

		if (!$shouldUpdate)
		{
			$entryDb->indexToSearchIndex();
			
			return $entryDb->getIntId();
		}
		
		return myEntryUtils::index($entryDb);
	}

	/**
	 * Clone an entry with optional attributes to apply to the clone
	 * 
	 * @action clone
	 * @param string $entryId Id of entry to clone
	 * @param BorhanBaseEntryCloneOptionsArray $cloneOptions
	 * @param BorhanBaseEntry $updateEntry [optional] Attributes from these entry will be updated into the cloned entry
	 * @return BorhanBaseEntry The cloned entry
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	public function cloneAction( $entryId, $cloneOptions=null)
	{
		// Reset criteria filters such that it will be
		entryPeer::setUseCriteriaFilter(false);
		categoryEntryPeer::setUseCriteriaFilter(false);

		// Get the entry
		$coreEntry = entryPeer::retrieveByPK( $entryId );
		if ( ! $coreEntry )
		{
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
		}
//		$coreClonedOptionsArray = array();
//		foreach ($cloneOptions as $item)
//		{
//			$coreClonedOptionsArray[] = $item->toObject();
//		}

		$coreClonedOptionsArray = $cloneOptions->toObjectsArray();

		// Copy the entry into a new one based on the given partner data.
		$clonedEntry = myEntryUtils::copyEntry($coreEntry, $this->getPartner(), $coreClonedOptionsArray);
		return $this->getEntry($clonedEntry->getId());
	}

	/**
	 * This action delivers all data relevant for player
	 * @action getPlaybackContext
	 * @param string $entryId
	 * @param BorhanPlaybackContextOptions $contextDataParams
	 * @return BorhanPlaybackContext
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	function getPlaybackContextAction($entryId, BorhanPlaybackContextOptions $contextDataParams)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		if ($dbEntry->getStatus() != entryStatus::READY)
		{
			// the purpose of this is to solve a case in which a player attempts to play a non-ready entry,
			// and the request becomes cached for a long time, preventing playback even after the entry
			// becomes ready
			kApiCache::setExpiry(60);
		}

		$parentEntryId = $dbEntry->getSecurityParentId();
		if ($parentEntryId)
		{
			$dbEntry = $dbEntry->getParentEntry();
			if(!$dbEntry)
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $parentEntryId);
		}

		$asset = null;
		if ($contextDataParams->flavorAssetId)
		{
			$asset = assetPeer::retrieveById($contextDataParams->flavorAssetId);
			if (!$asset)
				throw new BorhanAPIException(BorhanErrors::FLAVOR_ASSET_ID_NOT_FOUND, $contextDataParams->flavorAssetId);
		}

		$contextDataHelper = new kContextDataHelper($dbEntry, $this->getPartner(), $asset);

		if ($dbEntry->getAccessControl() && $dbEntry->getAccessControl()->hasRules())
			$accessControlScope = $dbEntry->getAccessControl()->getScope();
		else
			$accessControlScope = new accessControlScope();
		$contextDataParams->toObject($accessControlScope);

		$contextDataHelper->buildContextDataResult($accessControlScope, $contextDataParams->flavorTags, $contextDataParams->streamerType, $contextDataParams->mediaProtocol, true);
		if ($contextDataHelper->getDisableCache())
			BorhanResponseCacher::disableCache();

		$isScheduledNow = $dbEntry->isScheduledNow($contextDataParams->time);
		if (!($isScheduledNow) && $this->getKs() ){
			// in case the sview is defined in the ks simulate schedule now true to allow player to pass verification
			if ( $this->getKs()->verifyPrivileges(ks::PRIVILEGE_VIEW, ks::PRIVILEGE_WILDCARD) ||
				$this->getKs()->verifyPrivileges(ks::PRIVILEGE_VIEW, $entryId)) {
				$isScheduledNow = true;
			}
		}

		$playbackContextDataHelper = new kPlaybackContextDataHelper();
		$playbackContextDataHelper->setIsScheduledNow($isScheduledNow);
		$playbackContextDataHelper->constructPlaybackContextResult($contextDataHelper, $dbEntry);

		$result = new BorhanPlaybackContext();
		$result->fromObject($playbackContextDataHelper->getPlaybackContext());
		$result->actions = BorhanRuleActionArray::fromDbArray($contextDataHelper->getContextDataResult()->getActions());

		return $result;
	}

}

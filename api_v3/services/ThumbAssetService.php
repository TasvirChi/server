<?php

/**
 * Retrieve information and invoke actions on Thumb Asset
 *
 * @service thumbAsset
 * @package api
 * @subpackage services
 */
class ThumbAssetService extends BorhanAssetService
{
	protected function getEnabledMediaTypes()
	{
		$liveStreamTypes = BorhanPluginManager::getExtendedTypes(entryPeer::OM_CLASS, BorhanEntryType::LIVE_STREAM);
		
		$mediaTypes = array_merge($liveStreamTypes, parent::getEnabledMediaTypes());
		$mediaTypes[] = BorhanEntryType::AUTOMATIC;
		
		$mediaTypes = array_unique($mediaTypes);
		return $mediaTypes;
	}
	
	protected function borhanNetworkAllowed($actionName)
	{
		if(
			$actionName == 'get' ||
			$actionName == 'list' ||
			$actionName == 'getByEntryId' ||
			$actionName == 'getUrl' ||
			$actionName == 'getWebPlayableByEntryId' ||
			$actionName == 'generateByEntryId' ||
			$actionName == 'regenerate'
			)
		{
			$this->partnerGroup .= ',0';
			return true;
		}
			
		return parent::borhanNetworkAllowed($actionName);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::partnerRequired()
	 */
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'serve') 
			return false;

		if ($actionName === 'serveByEntryId') 
			return false;
		
		return parent::partnerRequired($actionName);
	}
	
    /**
     * Add thumbnail asset
     *
     * @action add
     * @param string $entryId
     * @param BorhanThumbAsset $thumbAsset
     * @return BorhanThumbAsset
     * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
     * @throws BorhanErrors::THUMB_ASSET_ALREADY_EXISTS
	 * @throws BorhanErrors::UPLOAD_TOKEN_INVALID_STATUS_FOR_ADD_ENTRY
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN
	 * @throws BorhanErrors::RECORDED_WEBCAM_FILE_NOT_FOUND
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED
	 * @validateUser entry entryId edit
     */
    function addAction($entryId, BorhanThumbAsset $thumbAsset)
    {
    	$dbEntry = entryPeer::retrieveByPK($entryId);
    	if(!$dbEntry || !in_array($dbEntry->getType(), $this->getEnabledMediaTypes()) || ($dbEntry->getType() == entryType::MEDIA_CLIP && !in_array($dbEntry->getMediaType(), array(BorhanMediaType::VIDEO, BorhanMediaType::AUDIO, BorhanMediaType::LIVE_STREAM_FLASH))))
    		throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
		
    	if($thumbAsset->thumbParamsId)
    	{
    		$dbThumbAsset = assetPeer::retrieveByEntryIdAndParams($entryId, $thumbAsset->thumbParamsId);
    		if($dbThumbAsset)
    			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ALREADY_EXISTS, $dbThumbAsset->getId(), $thumbAsset->thumbParamsId);
    	}
    	
    	$dbThumbAsset = $thumbAsset->toInsertableObject();
    	/* @var $dbThumbAsset thumbAsset */
    	
		$dbThumbAsset->setEntryId($entryId);
		$dbThumbAsset->setPartnerId($dbEntry->getPartnerId());
		$dbThumbAsset->setStatus(thumbAsset::ASSET_STATUS_QUEUED);
		$dbThumbAsset->save();

		$thumbAsset = BorhanThumbAsset::getInstance($dbThumbAsset, $this->getResponseProfile());
		return $thumbAsset;
    }
    
    /**
     * Update content of thumbnail asset
     *
     * @action setContent
     * @param string $id
     * @param BorhanContentResource $contentResource
     * @return BorhanThumbAsset
     * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::UPLOAD_TOKEN_INVALID_STATUS_FOR_ADD_ENTRY
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN
	 * @throws BorhanErrors::RECORDED_WEBCAM_FILE_NOT_FOUND
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED
	 * @validateUser asset::entry id edit 
     */
    function setContentAction($id, BorhanContentResource $contentResource)
    {
   		$dbThumbAsset = assetPeer::retrieveById($id);
   		if (!$dbThumbAsset || !($dbThumbAsset instanceof thumbAsset))
   			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $id);
    	
		$dbEntry = $dbThumbAsset->getentry();
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $dbThumbAsset->getEntryId());
			
		
		
   		$previousStatus = $dbThumbAsset->getStatus();
		$contentResource->validateEntry($dbThumbAsset->getentry());
		$contentResource->validateAsset($dbThumbAsset);
		$kContentResource = $contentResource->toObject();
    	$this->attachContentResource($dbThumbAsset, $kContentResource);
		$contentResource->entryHandled($dbThumbAsset->getentry());
		kEventsManager::raiseEvent(new kObjectDataChangedEvent($dbThumbAsset));
		
    	$newStatuses = array(
    		thumbAsset::ASSET_STATUS_READY,
    		thumbAsset::ASSET_STATUS_VALIDATING,
    		thumbAsset::ASSET_STATUS_TEMP,
    	);
    	
    	if($previousStatus == thumbAsset::ASSET_STATUS_QUEUED && in_array($dbThumbAsset->getStatus(), $newStatuses))
   			kEventsManager::raiseEvent(new kObjectAddedEvent($dbThumbAsset));
   		
		$thumbAssetsCount = assetPeer::countThumbnailsByEntryId($dbThumbAsset->getEntryId());
		
		$defaultThumbKey = $dbEntry->getSyncKey(entry::FILE_SYNC_ENTRY_SUB_TYPE_THUMB);
    		
 		//If the thums has the default tag or the entry is in no content and this is the first thumb
 		if($dbThumbAsset->hasTag(thumbParams::TAG_DEFAULT_THUMB) || ($dbEntry->getStatus() == BorhanEntryStatus::NO_CONTENT 
 			&& $thumbAssetsCount == 1 && !kFileSyncUtils::fileSync_exists($defaultThumbKey)))
		{
			$this->setAsDefaultAction($dbThumbAsset->getId());
		}
		
		$thumbAsset = BorhanThumbAsset::getInstance($dbThumbAsset, $this->getResponseProfile());
		return $thumbAsset;
    }
	
    /**
     * Update thumbnail asset
     *
     * @action update
     * @param string $id
     * @param BorhanThumbAsset $thumbAsset
     * @return BorhanThumbAsset
     * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
     * @validateUser asset::entry id edit 
     */
    function updateAction($id, BorhanThumbAsset $thumbAsset)
    {
		$dbThumbAsset = assetPeer::retrieveById($id);
		if (!$dbThumbAsset || !($dbThumbAsset instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $id);
    	
		$dbEntry = $dbThumbAsset->getentry();
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $dbThumbAsset->getEntryId());
			
		
		
    	$dbThumbAsset = $thumbAsset->toUpdatableObject($dbThumbAsset);
   		$dbThumbAsset->save();
		
		if($dbEntry->getCreateThumb() && $dbThumbAsset->hasTag(thumbParams::TAG_DEFAULT_THUMB))
			$this->setAsDefaultAction($dbThumbAsset->getId());
			
		$thumbAsset = BorhanThumbAsset::getInstance($dbThumbAsset, $this->getResponseProfile());
		return $thumbAsset;
    }
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param string $fullPath
	 * @param bool $copyOnly
	 */
	protected function attachFile(thumbAsset $thumbAsset, $fullPath, $copyOnly = false)
	{
		$ext = pathinfo($fullPath, PATHINFO_EXTENSION);
		
		$thumbAsset->incrementVersion();
		$thumbAsset->setFileExt($ext);
		$thumbAsset->setSize(filesize($fullPath));
		$thumbAsset->save();
		
		$syncKey = $thumbAsset->getSyncKey(thumbAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
		
		try {
			kFileSyncUtils::moveFromFile($fullPath, $syncKey, true, $copyOnly);
		}
		catch (Exception $e) {
			
			if($thumbAsset->getStatus() == thumbAsset::ASSET_STATUS_QUEUED || $thumbAsset->getStatus() == thumbAsset::ASSET_STATUS_NOT_APPLICABLE)
			{
				$thumbAsset->setDescription($e->getMessage());
				$thumbAsset->setStatus(thumbAsset::ASSET_STATUS_ERROR);
				$thumbAsset->save();
			}												
			throw $e;
		}

		$finalPath = kFileSyncUtils::getLocalFilePathForKey($syncKey);
		list($width, $height, $type, $attr) = getimagesize($finalPath);
		
		$thumbAsset->setWidth($width);
		$thumbAsset->setHeight($height);
		$thumbAsset->setSize(filesize($finalPath));
		
		$thumbAsset->setStatusLocalReady();
		$thumbAsset->save();
	}
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param string $url
	 */
	protected function attachUrl(thumbAsset $thumbAsset, $url)
	{
    	$fullPath = myContentStorage::getFSUploadsPath() . '/' . $thumbAsset->getId() . '.jpg';
		if (KCurlWrapper::getDataFromFile($url, $fullPath))
			return $this->attachFile($thumbAsset, $fullPath);
			
		if($thumbAsset->getStatus() == thumbAsset::ASSET_STATUS_QUEUED || $thumbAsset->getStatus() == thumbAsset::ASSET_STATUS_NOT_APPLICABLE)
		{
			$thumbAsset->setDescription("Failed downloading file[$url]");
			$thumbAsset->setStatus(thumbAsset::ASSET_STATUS_ERROR);
			$thumbAsset->save();
		}
		
		throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_DOWNLOAD_FAILED, $url);
    }
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param kUrlResource $contentResource
	 */
	protected function attachUrlResource(thumbAsset $thumbAsset, kUrlResource $contentResource)
	{
    	$this->attachUrl($thumbAsset, $contentResource->getUrl());
    }
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param kLocalFileResource $contentResource
	 */
	protected function attachLocalFileResource(thumbAsset $thumbAsset, kLocalFileResource $contentResource)
	{
		if($contentResource->getIsReady())
			return $this->attachFile($thumbAsset, $contentResource->getLocalFilePath(), $contentResource->getKeepOriginalFile());
			
		$thumbAsset->setStatus(asset::ASSET_STATUS_IMPORTING);
		$thumbAsset->save();
		
		$contentResource->attachCreatedObject($thumbAsset);
    }
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param FileSyncKey $srcSyncKey
	 */
	protected function attachFileSync(thumbAsset $thumbAsset, FileSyncKey $srcSyncKey)
	{
		$thumbAsset->incrementVersion();
		$thumbAsset->save();
		
        $newSyncKey = $thumbAsset->getSyncKey(thumbAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
        kFileSyncUtils::createSyncFileLinkForKey($newSyncKey, $srcSyncKey);
                
		$finalPath = kFileSyncUtils::getLocalFilePathForKey($newSyncKey);
		list($width, $height, $type, $attr) = getimagesize($finalPath);
		
		$thumbAsset->setWidth($width);
		$thumbAsset->setHeight($height);
		$thumbAsset->setSize(filesize($finalPath));
		
		$thumbAsset->setStatusLocalReady();
		$thumbAsset->save();
    }
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param kFileSyncResource $contentResource
	 */
	protected function attachFileSyncResource(thumbAsset $thumbAsset, kFileSyncResource $contentResource)
	{
    	$syncable = kFileSyncObjectManager::retrieveObject($contentResource->getFileSyncObjectType(), $contentResource->getObjectId());
    	$srcSyncKey = $syncable->getSyncKey($contentResource->getObjectSubType(), $contentResource->getVersion());
    	
        return $this->attachFileSync($thumbAsset, $srcSyncKey);
    }
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param IRemoteStorageResource $contentResource
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 */
	protected function attachRemoteStorageResource(thumbAsset $thumbAsset, IRemoteStorageResource $contentResource)
	{
		$resources = $contentResource->getResources();
		
		$thumbAsset->setFileExt($contentResource->getFileExt());
        $thumbAsset->incrementVersion();
		$thumbAsset->setStatusLocalReady();
        $thumbAsset->save();
        	
        $syncKey = $thumbAsset->getSyncKey(thumbAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
		foreach($resources as $currentResource)
		{
			$storageProfile = StorageProfilePeer::retrieveByPK($currentResource->getStorageProfileId());
			$fileSync = kFileSyncUtils::createReadyExternalSyncFileForKey($syncKey, $currentResource->getUrl(), $storageProfile);
		}
    }
    
    
	/**
	 * @param thumbAsset $thumbAsset
	 * @param kContentResource $contentResource
	 * @throws BorhanErrors::UPLOAD_TOKEN_INVALID_STATUS_FOR_ADD_ENTRY
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN
	 * @throws BorhanErrors::RECORDED_WEBCAM_FILE_NOT_FOUND
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED
	 */
	protected function attachContentResource(thumbAsset $thumbAsset, kContentResource $contentResource)
	{
    	switch($contentResource->getType())
    	{
			case 'kUrlResource':
				return $this->attachUrlResource($thumbAsset, $contentResource);
				
			case 'kLocalFileResource':
				return $this->attachLocalFileResource($thumbAsset, $contentResource);
				
			case 'kFileSyncResource':
				return $this->attachFileSyncResource($thumbAsset, $contentResource);
				
			case 'kRemoteStorageResource':
			case 'kRemoteStorageResources':
				return $this->attachRemoteStorageResource($thumbAsset, $contentResource);
				
			default:
				$msg = "Resource of type [" . get_class($contentResource) . "] is not supported";
				BorhanLog::err($msg);
				
				if($thumbAsset->getStatus() == thumbAsset::ASSET_STATUS_QUEUED || $thumbAsset->getStatus() == thumbAsset::ASSET_STATUS_NOT_APPLICABLE)
				{
					$thumbAsset->setDescription($msg);
					$thumbAsset->setStatus(asset::ASSET_STATUS_ERROR);
					$thumbAsset->save();
				}
				
				throw new BorhanAPIException(BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED, get_class($contentResource));
    	}
    }
    
    
	/**
	 * Serves thumbnail by entry id and thumnail params id
	 *  
	 * @action serveByEntryId
	 * @param string $entryId
	 * @param int $thumbParamId if not set, default thumbnail will be used.
	 * @return file
	 * 
	 * @throws BorhanErrors::THUMB_ASSET_IS_NOT_READY
	 * @throws BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	public function serveByEntryIdAction($entryId, $thumbParamId = null)
	{
		$entry = null;
		if (!kCurrentContext::$ks)
		{
			$entry = kCurrentContext::initPartnerByEntryId($entryId);
			
			if (!$entry || $entry->getStatus() == entryStatus::DELETED)
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
				
			// enforce entitlement
			$this->setPartnerFilters(kCurrentContext::getCurrentPartnerId());
			kEntitlementUtils::initEntitlementEnforcement();
			
			if(!kEntitlementUtils::isEntryEntitled($entry))
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);				
		}
		else 
		{	
			$entry = entryPeer::retrieveByPK($entryId);
		}
		
		if (!$entry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		$securyEntryHelper = new KSecureEntryHelper($entry, kCurrentContext::$ks, null, ContextType::THUMBNAIL);
		$securyEntryHelper->validateAccessControl();
		
		$fileName = $entry->getId() . '.jpg';
		
		if(is_null($thumbParamId))
			return $this->serveFile($entry, entry::FILE_SYNC_ENTRY_SUB_TYPE_THUMB, $fileName, $entryId);
		
		$thumbAsset = assetPeer::retrieveByEntryIdAndParams($entryId, $thumbParamId);
		if(!$thumbAsset)
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND, $thumbParamId);
		
		return $this->serveAsset($thumbAsset, $fileName);
	}

	/**
	 * Serves thumbnail by its id
	 *  
	 * @action serve
	 * @param string $thumbAssetId
	 * @param int $version
	 * @param BorhanThumbParams $thumbParams
	 * @param BorhanThumbnailServeOptions $options
	 * @return file
	 *  
	 * @throws BorhanErrors::THUMB_ASSET_IS_NOT_READY
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 */
	public function serveAction($thumbAssetId, $version = null, BorhanThumbParams $thumbParams = null, BorhanThumbnailServeOptions $options = null)
	{
		if (!kCurrentContext::$ks)
		{
			$thumbAsset = kCurrentContext::initPartnerByAssetId($thumbAssetId);
			
			if (!$thumbAsset || $thumbAsset->getStatus() == asset::ASSET_STATUS_DELETED)
				throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
				
			// enforce entitlement
			$this->setPartnerFilters(kCurrentContext::getCurrentPartnerId());
			kEntitlementUtils::initEntitlementEnforcement();
		}
		else 
		{	
			$thumbAsset = assetPeer::retrieveById($thumbAssetId);
		}
			
		if (!$thumbAsset || !($thumbAsset instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
			
		$entry = entryPeer::retrieveByPK($thumbAsset->getEntryId());
		if(!$entry)
		{
			//we will throw thumb asset not found, as the user is not entitled, and should not know that the entry exists.
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
		}

		$referrer = null;
		if($options && $options->referrer)
			$referrer = $options->referrer;

		$securyEntryHelper = new KSecureEntryHelper($entry, kCurrentContext::$ks, $referrer, ContextType::THUMBNAIL);
		$securyEntryHelper->validateAccessControl();

		$ext = $thumbAsset->getFileExt();
		if(is_null($ext))
			$ext = 'jpg';
			
		$fileName = $thumbAsset->getEntryId()."_" . $thumbAsset->getId() . ".$ext";
		if(!$thumbParams)
		{
			if($options && $options->download)
				header("Content-Disposition: attachment; filename=\"$fileName\"");
			return $this->serveAsset($thumbAsset, $fileName, $version);
		}
			
		$thumbParams->validate();
		
		$syncKey = $thumbAsset->getSyncKey(asset::FILE_SYNC_ASSET_SUB_TYPE_ASSET, $version);
		if(!kFileSyncUtils::fileSync_exists($syncKey))
			throw new BorhanAPIException(BorhanErrors::FILE_DOESNT_EXIST);
			
		list($fileSync, $local) = kFileSyncUtils::getReadyFileSyncForKey($syncKey, true, false);
		/* @var $fileSync FileSync */
		
		if(!$local)
		{
			if ( !in_array($fileSync->getDc(), kDataCenterMgr::getDcIds()) )
				throw new BorhanAPIException(BorhanErrors::FILE_DOESNT_EXIST);
				
			$remoteUrl = kDataCenterMgr::getRedirectExternalUrl($fileSync);
			BorhanLog::info("Redirecting to [$remoteUrl]");
			header("Location: $remoteUrl");
			die;
		}
		
		$filePath = $fileSync->getFullPath();
		
		$thumbVersion = $thumbAsset->getId() . '_' . $version;
		$tempThumbPath = myEntryUtils::resizeEntryImage($entry, $thumbVersion, 
			$thumbParams->width, 
			$thumbParams->height, 
			$thumbParams->cropType, 
			$thumbParams->backgroundColor, 
			null, 
			$thumbParams->quality,
			$thumbParams->cropX, 
			$thumbParams->cropY, 
			$thumbParams->cropWidth, 
			$thumbParams->cropHeight, 
			-1, 0, -1, 
			$filePath, 
			$thumbParams->density, 
			$thumbParams->stripProfiles, 
			null);
		
		if($options && $options->download)
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			
		$mimeType = kFile::mimeType($tempThumbPath);
		return $this->dumpFile($tempThumbPath, $mimeType); 
	}
	
	/**
	 * Tags the thumbnail as DEFAULT_THUMB and removes that tag from all other thumbnail assets of the entry.
	 * Create a new file sync link on the entry thumbnail that points to the thumbnail asset file sync.
	 *  
	 * @action setAsDefault
	 * @param string $thumbAssetId
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @validateUser asset::entry thumbAssetId edit 
	 */
	public function setAsDefaultAction($thumbAssetId)
	{
		$thumbAsset = assetPeer::retrieveById($thumbAssetId);
		if (!$thumbAsset || !($thumbAsset instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
		
		kBusinessConvertDL::setAsDefaultThumbAsset($thumbAsset);
	}

	/**
	 * @action generateByEntryId
	 * @param string $entryId
	 * @param int $destThumbParamsId indicate the id of the ThumbParams to be generate this thumbnail by
	 * @return BorhanThumbAsset
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED
	 * @throws BorhanErrors::ENTRY_MEDIA_TYPE_NOT_SUPPORTED
	 * @throws BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND
	 * @throws BorhanErrors::INVALID_ENTRY_STATUS
	 * @throws BorhanErrors::THUMB_ASSET_IS_NOT_READY
	 * @validateUser entry entryId edit
	 */
	public function generateByEntryIdAction($entryId, $destThumbParamsId)
	{
		$entry = entryPeer::retrieveByPK($entryId);
		if(!$entry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
			
		if (!in_array($entry->getType(), $this->getEnabledMediaTypes()))
			throw new BorhanAPIException(BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED, $entry->getType());
		if ($entry->getMediaType() != entry::ENTRY_MEDIA_TYPE_VIDEO)
			throw new BorhanAPIException(BorhanErrors::ENTRY_MEDIA_TYPE_NOT_SUPPORTED, $entry->getMediaType());
						
		
			
		$validStatuses = array(
			entryStatus::ERROR_CONVERTING,
			entryStatus::PRECONVERT,
			entryStatus::READY,
		);
		
		if (!in_array($entry->getStatus(), $validStatuses))
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_STATUS);
			
		$destThumbParams = assetParamsPeer::retrieveByPK($destThumbParamsId);
		if(!$destThumbParams)
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND, $destThumbParamsId);

		$dbThumbAsset = kBusinessPreConvertDL::decideThumbGenerate($entry, $destThumbParams);
		if(!$dbThumbAsset)
			return null;
			
		$thumbAsset = new BorhanThumbAsset();
		$thumbAsset->fromObject($dbThumbAsset, $this->getResponseProfile());
		return $thumbAsset;
	}

	/**
	 * @action generate
	 * @param string $entryId
	 * @param BorhanThumbParams $thumbParams
	 * @param string $sourceAssetId id of the source asset (flavor or thumbnail) to be used as source for the thumbnail generation
	 * @return BorhanThumbAsset
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED
	 * @throws BorhanErrors::ENTRY_MEDIA_TYPE_NOT_SUPPORTED
	 * @throws BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND
	 * @throws BorhanErrors::INVALID_ENTRY_STATUS
	 * @throws BorhanErrors::THUMB_ASSET_IS_NOT_READY
	 * @validateUser entry entryId edit
	 */
	public function generateAction($entryId, BorhanThumbParams $thumbParams, $sourceAssetId = null)
	{
		$entry = entryPeer::retrieveByPK($entryId);
		if(!$entry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
			
		if (!in_array($entry->getType(), $this->getEnabledMediaTypes()))
			throw new BorhanAPIException(BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED, $entry->getType());
			
		if ($entry->getMediaType() != entry::ENTRY_MEDIA_TYPE_VIDEO)
			throw new BorhanAPIException(BorhanErrors::ENTRY_MEDIA_TYPE_NOT_SUPPORTED, $entry->getMediaType());
			
		
		
		$validStatuses = array(
			entryStatus::ERROR_CONVERTING,
			entryStatus::PRECONVERT,
			entryStatus::READY,
		);
		
		if (!in_array($entry->getStatus(), $validStatuses))
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_STATUS);
			
		$destThumbParams = new thumbParams();
		$thumbParams->toUpdatableObject($destThumbParams);

		$srcAsset = kBusinessPreConvertDL::getSourceAssetForGenerateThumbnail($sourceAssetId, $destThumbParams->getSourceParamsId(), $entryId);		
		if (is_null($srcAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_IS_NOT_READY);
		
		$sourceFileSyncKey = $srcAsset->getSyncKey(flavorAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET); 
		list($fileSync, $local) = kFileSyncUtils::getReadyFileSyncForKey($sourceFileSyncKey,true);
		/* @var $fileSync FileSync */
		
		if(is_null($fileSync))
		{
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_IS_NOT_READY);
		}
		
		if($fileSync->getFileType() == FileSync::FILE_SYNC_FILE_TYPE_URL)
		{
			throw new BorhanAPIException(BorhanErrors::SOURCE_FILE_REMOTE);
		}
		
		if(!$local)
		{
			kFileUtils::dumpApiRequest(kDataCenterMgr::getRemoteDcExternalUrl($fileSync));
		}
		
		$dbThumbAsset = kBusinessPreConvertDL::decideThumbGenerate($entry, $destThumbParams, null, $sourceAssetId, true , $srcAsset);
		if(!$dbThumbAsset)
			return null;
			
		$thumbAsset = new BorhanThumbAsset();
		$thumbAsset->fromObject($dbThumbAsset, $this->getResponseProfile());
		return $thumbAsset;
	}

	/**
	 * @action regenerate
	 * @param string $thumbAssetId
	 * @return BorhanThumbAsset
	 * 
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED
	 * @throws BorhanErrors::ENTRY_MEDIA_TYPE_NOT_SUPPORTED
	 * @throws BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND
	 * @throws BorhanErrors::INVALID_ENTRY_STATUS
	 * @validateUser asset::entry thumbAssetId edit
	 */
	public function regenerateAction($thumbAssetId)
	{
		$thumbAsset = assetPeer::retrieveById($thumbAssetId);
		if (!$thumbAsset || !($thumbAsset instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
			
		if(is_null($thumbAsset->getFlavorParamsId()))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND, null);
			
		$destThumbParams = assetParamsPeer::retrieveByPK($thumbAsset->getFlavorParamsId());
		if(!$destThumbParams)
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_PARAMS_ID_NOT_FOUND, $thumbAsset->getFlavorParamsId());
			
		$entry = $thumbAsset->getentry();
		if (!in_array($entry->getType(), $this->getEnabledMediaTypes()))
			throw new BorhanAPIException(BorhanErrors::ENTRY_TYPE_NOT_SUPPORTED, $entry->getType());
		if ($entry->getMediaType() != entry::ENTRY_MEDIA_TYPE_VIDEO)
			throw new BorhanAPIException(BorhanErrors::ENTRY_MEDIA_TYPE_NOT_SUPPORTED, $entry->getMediaType());
						
		
			
		$validStatuses = array(
			entryStatus::ERROR_CONVERTING,
			entryStatus::PRECONVERT,
			entryStatus::READY,
		);
		
		if (!in_array($entry->getStatus(), $validStatuses))
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_STATUS);

		$dbThumbAsset = kBusinessPreConvertDL::decideThumbGenerate($entry, $destThumbParams);
		if(!$dbThumbAsset)
			return null;
			
		$thumbAsset = new BorhanThumbAsset();
		$thumbAsset->fromObject($dbThumbAsset, $this->getResponseProfile());
		return $thumbAsset;
	}
	
	/**
	 * @action get
	 * @param string $thumbAssetId
	 * @return BorhanThumbAsset
	 * 
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 */
	public function getAction($thumbAssetId)
	{
		$thumbAssetsDb = assetPeer::retrieveById($thumbAssetId);
		if (!$thumbAssetsDb || !($thumbAssetsDb instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
			
		if(kEntitlementUtils::getEntitlementEnforcement())
		{
			$entry = entryPeer::retrieveByPK($thumbAssetsDb->getEntryId());
			if(!$entry)
			{
				//we will throw thumb asset not found, as the user is not entitled, and should not know that the entry exists.
				throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
			}	
		}
		
		$thumbAssets = BorhanThumbAsset::getInstance($thumbAssetsDb, $this->getResponseProfile());
		return $thumbAssets;
	}
	
	/**
	 * @action getByEntryId
	 * @param string $entryId
	 * @return BorhanThumbAssetArray
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @deprecated Use thumbAsset.list instead
	 */
	public function getByEntryIdAction($entryId)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
			
		// get the thumb assets for this entry
		$c = new Criteria();
		$c->add(assetPeer::ENTRY_ID, $entryId);
		
		//BMC currently does not support showing thumb asset extending types
		//$thumbTypes = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, assetType::THUMBNAIL);
		//$c->add(assetPeer::TYPE, $thumbTypes, Criteria::IN);
		
		$c->add(assetPeer::TYPE, assetType::THUMBNAIL, Criteria::EQUAL);
		
		$thumbAssetsDb = assetPeer::doSelect($c);
		$thumbAssets = BorhanThumbAssetArray::fromDbArray($thumbAssetsDb, $this->getResponseProfile());
		return $thumbAssets;
	}
	
	/**
	 * List Thumbnail Assets by filter and pager
	 * 
	 * @action list
	 * @param BorhanAssetFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanThumbAssetListResponse
	 */
	function listAction(BorhanAssetFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if(!$filter)
		{
			$filter = new BorhanThumbAssetFilter();
		}
		elseif(! $filter instanceof BorhanThumbAssetFilter)
		{
                        if(!is_subclass_of('BorhanThumbAssetFilter', get_class($filter)))
                            $filter = $filter->cast('BorhanAssetFilter');
		    
			$filter = $filter->cast('BorhanThumbAssetFilter');
		}
			
		if(!$pager)
		{
			$pager = new BorhanFilterPager();
		}
			
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, assetType::THUMBNAIL);
		return $filter->getTypeListResponse($pager, $this->getResponseProfile(), $types);
	}
	
	/**
	 * @action addFromUrl
	 * @param string $entryId
	 * @param string $url
	 * @return BorhanThumbAsset
	 * 
	 * @deprecated use thumbAsset.add and thumbAsset.setContent instead
	 */
	public function addFromUrlAction($entryId, $url)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
			
		
		
		$ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
		
		$dbThumbAsset = new thumbAsset();
		$dbThumbAsset->setPartnerId($dbEntry->getPartnerId());
		$dbThumbAsset->setEntryId($dbEntry->getId());
		$dbThumbAsset->setStatus(thumbAsset::ASSET_STATUS_QUEUED);
		$dbThumbAsset->setFileExt($ext);
		$dbThumbAsset->incrementVersion();
		$dbThumbAsset->save();
		
		$syncKey = $dbThumbAsset->getSyncKey(thumbAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
		kFileSyncUtils::file_put_contents($syncKey, file_get_contents($url));
		
		$finalPath = kFileSyncUtils::getLocalFilePathForKey($syncKey);
		list($width, $height, $type, $attr) = getimagesize($finalPath);
		
		$dbThumbAsset->setWidth($width);
		$dbThumbAsset->setHeight($height);
		$dbThumbAsset->setSize(filesize($finalPath));
		$dbThumbAsset->setStatusLocalReady();
		$dbThumbAsset->save();
		
		$thumbAssets = new BorhanThumbAsset();
		$thumbAssets->fromObject($dbThumbAsset, $this->getResponseProfile());
		return $thumbAssets;
	}
	
	/**
	 * @action addFromImage
	 * @param string $entryId
	 * @param file $fileData
	 * @return BorhanThumbAsset
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry entryId edit
	 */
	public function addFromImageAction($entryId, $fileData)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);
			
		
		
		$ext = pathinfo($fileData["name"], PATHINFO_EXTENSION);
		
		$dbThumbAsset = new thumbAsset();
		$dbThumbAsset->setPartnerId($dbEntry->getPartnerId());
		$dbThumbAsset->setEntryId($dbEntry->getId());
		$dbThumbAsset->setStatus(thumbAsset::ASSET_STATUS_QUEUED);
		$dbThumbAsset->setFileExt($ext);
		$dbThumbAsset->incrementVersion();
		$dbThumbAsset->save();
		
		$syncKey = $dbThumbAsset->getSyncKey(thumbAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
		kFileSyncUtils::moveFromFile($fileData["tmp_name"], $syncKey);
		
		$finalPath = kFileSyncUtils::getLocalFilePathForKey($syncKey);
		list($width, $height, $type, $attr) = getimagesize($finalPath);
		
		$dbThumbAsset->setWidth($width);
		$dbThumbAsset->setHeight($height);
		$dbThumbAsset->setSize(filesize($finalPath));
		$dbThumbAsset->setStatusLocalReady();
		$dbThumbAsset->save();
		
		$dbEntryThumbs = assetPeer::retrieveThumbnailsByEntryId($entryId);
    		
 		//If the thums has the default tag or the entry is in no content and this is the first thumb
		if($dbEntry->getCreateThumb() && 
			(
				$dbThumbAsset->hasTag(thumbParams::TAG_DEFAULT_THUMB) || 
		  		($dbEntry->getStatus() == BorhanEntryStatus::NO_CONTENT && count($dbEntryThumbs) == 1)
		  	)
		  )
				$this->setAsDefaultAction($dbThumbAsset->getId());
			
		$thumbAssets = new BorhanThumbAsset();
		$thumbAssets->fromObject($dbThumbAsset, $this->getResponseProfile());
		return $thumbAssets;
	}
	
	/**
	 * @action delete
	 * @param string $thumbAssetId
	 * 
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @validateUser asset::entry thumbAssetId edit
	 */
	public function deleteAction($thumbAssetId)
	{
		$thumbAssetDb = assetPeer::retrieveById($thumbAssetId);
		if (!$thumbAssetDb || !($thumbAssetDb instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);

		if(kEntitlementUtils::getEntitlementEnforcement())
		{
			$entry = entryPeer::retrieveByPK($thumbAssetDb->getEntryId());
			if(!$entry)
			{
				//we will throw thumb asset not found, as the user is not entitled, and should not know that the entry exists.
				throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $thumbAssetId);
			}	
		}
			
		if($thumbAssetDb->hasTag(thumbParams::TAG_DEFAULT_THUMB))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_IS_DEFAULT, $thumbAssetId);
		
		$entry = $thumbAssetDb->getEntry();
		if (!$entry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $thumbAssetDb->getEntryId());
			
		
		
		$thumbAssetDb->setStatus(thumbAsset::ASSET_STATUS_DELETED);
		$thumbAssetDb->setDeletedAt(time());
		$thumbAssetDb->save();
	}
	
	/**
	 * Get download URL for the asset
	 * 
	 * @action getUrl
	 * @param string $id
	 * @param int $storageId
	 * @param BorhanThumbParams $thumbParams
	 * @return string
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::THUMB_ASSET_IS_NOT_READY
	 */
	public function getUrlAction($id, $storageId = null, BorhanThumbParams $thumbParams = null)
	{
		$assetDb = assetPeer::retrieveById($id);
		if (!$assetDb || !($assetDb instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $id);

		$entry = entryPeer::retrieveByPK($assetDb->getEntryId());
		if(!$entry)
		{
			//we will throw thumb asset not found, as the user is not entitled, and should not know that the entry exists or entry does not exist.
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $id);
		}

		if ($assetDb->getStatus() != asset::ASSET_STATUS_READY)
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_IS_NOT_READY);
		
		$securyEntryHelper = new KSecureEntryHelper($entry, kCurrentContext::$ks, null, ContextType::THUMBNAIL);
		$securyEntryHelper->validateAccessControl();
		
		return $assetDb->getThumbnailUrl($securyEntryHelper, $storageId, $thumbParams);
	}
		
	/**
	 * Get remote storage existing paths for the asset
	 * 
	 * @action getRemotePaths
	 * @param string $id
	 * @return BorhanRemotePathListResponse
	 * @throws BorhanErrors::THUMB_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::THUMB_ASSET_IS_NOT_READY
	 */
	public function getRemotePathsAction($id)
	{
		$assetDb = assetPeer::retrieveById($id);
		if (!$assetDb || !($assetDb instanceof thumbAsset))
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $id);
			
		if(kEntitlementUtils::getEntitlementEnforcement())
		{
			$entry = entryPeer::retrieveByPK($assetDb->getEntryId());
			if(!$entry)
			{
				//we will throw thumb asset not found, as the user is not entitled, and should not know that the entry exists.
				throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_ID_NOT_FOUND, $id);
			}	
		}

		if ($assetDb->getStatus() != asset::ASSET_STATUS_READY)
			throw new BorhanAPIException(BorhanErrors::THUMB_ASSET_IS_NOT_READY);

		$c = new Criteria();
		$c->add(FileSyncPeer::OBJECT_TYPE, FileSyncObjectType::ASSET);
		$c->add(FileSyncPeer::OBJECT_SUB_TYPE, asset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
		$c->add(FileSyncPeer::OBJECT_ID, $id);
		$c->add(FileSyncPeer::VERSION, $assetDb->getVersion());
		$c->add(FileSyncPeer::PARTNER_ID, $assetDb->getPartnerId());
		$c->add(FileSyncPeer::STATUS, FileSync::FILE_SYNC_STATUS_READY);
		$c->add(FileSyncPeer::FILE_TYPE, FileSync::FILE_SYNC_FILE_TYPE_URL);
		$fileSyncs = FileSyncPeer::doSelect($c);
			
		$listResponse = new BorhanRemotePathListResponse();
		$listResponse->objects = BorhanRemotePathArray::fromDbArray($fileSyncs, $this->getResponseProfile());
		$listResponse->totalCount = count($listResponse->objects);
		return $listResponse;
	}

	/**
	 * manually export an asset
	 *
	 * @action export
	 * @param string $assetId
	 * @param int $storageProfileId
	 * @throws BorhanErrors::INVALID_FLAVOR_ASSET_ID
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::INTERNAL_SERVERL_ERROR
	 * @return BorhanFlavorAsset The exported asset
	 */
	public function exportAction ( $assetId , $storageProfileId )
	{
		return parent::exportAction($assetId, $storageProfileId);
	}
	
}

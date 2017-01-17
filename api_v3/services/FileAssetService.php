<?php

/**
 * Manage file assets
 *
 * @service fileAsset
 */
class FileAssetService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('FileAsset');
		$this->applyPartnerFilterForClass('uiConf');
	}
	
	/**
	 * Add new file asset
	 * 
	 * @action add
	 * @param BorhanFileAsset $fileAsset
	 * @return BorhanFileAsset
	 */
	function addAction(BorhanFileAsset $fileAsset)
	{
		$dbFileAsset = $fileAsset->toInsertableObject();
		$dbFileAsset->setPartnerId($this->getPartnerId());
		$dbFileAsset->setStatus(BorhanFileAssetStatus::PENDING);
		$dbFileAsset->save();
		
		$fileAsset = new BorhanFileAsset();
		$fileAsset->fromObject($dbFileAsset, $this->getResponseProfile());
		return $fileAsset;
	}
	
	/**
	 * Get file asset by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanFileAsset
	 * 
	 * @throws BorhanErrors::FILE_ASSET_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbFileAsset = FileAssetPeer::retrieveByPK($id);
		if (!$dbFileAsset)
			throw new BorhanAPIException(BorhanErrors::FILE_ASSET_ID_NOT_FOUND, $id);
			
		$fileAsset = new BorhanFileAsset();
		$fileAsset->fromObject($dbFileAsset, $this->getResponseProfile());
		return $fileAsset;
	}
	
	/**
	 * Update file asset by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanFileAsset $fileAsset
	 * @return BorhanFileAsset
	 * 
	 * @throws BorhanErrors::FILE_ASSET_ID_NOT_FOUND
	 */
	function updateAction($id, BorhanFileAsset $fileAsset)
	{
		$dbFileAsset = FileAssetPeer::retrieveByPK($id);
		if (!$dbFileAsset)
			throw new BorhanAPIException(BorhanErrors::FILE_ASSET_ID_NOT_FOUND, $id);
		
		$fileAsset->toUpdatableObject($dbFileAsset);
		$dbFileAsset->save();
		
		$fileAsset = new BorhanFileAsset();
		$fileAsset->fromObject($dbFileAsset, $this->getResponseProfile());
		return $fileAsset;
	}
	
	/**
	 * Delete file asset by id
	 * 
	 * @action delete
	 * @param int $id
	 * 
	 * @throws BorhanErrors::FILE_ASSET_ID_NOT_FOUND
	 */
	function deleteAction($id)
	{
		$dbFileAsset = FileAssetPeer::retrieveByPK($id);
		if (!$dbFileAsset)
			throw new BorhanAPIException(BorhanErrors::FILE_ASSET_ID_NOT_FOUND, $id);

		$dbFileAsset->setStatus(BorhanFileAssetStatus::DELETED);
		$dbFileAsset->save();
	}

	/**
	 * Serve file asset by id
	 *  
	 * @action serve
	 * @param int $id
	 * @return file
	 *  
	 * @throws BorhanErrors::FILE_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::FILE_DOESNT_EXIST
	 */
	public function serveAction($id)
	{
		$dbFileAsset = FileAssetPeer::retrieveByPK($id);
		if (!$dbFileAsset)
			throw new BorhanAPIException(BorhanErrors::FILE_ASSET_ID_NOT_FOUND, $id);
		
		return $this->serveFile($dbFileAsset, FileAsset::FILE_SYNC_ASSET, $dbFileAsset->getName());
	}
	
    /**
     * Set content of file asset
     *
     * @action setContent
     * @param string $id
     * @param BorhanContentResource $contentResource
     * @return BorhanFileAsset
	 * @throws BorhanErrors::FILE_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN
	 * @throws BorhanErrors::RECORDED_WEBCAM_FILE_NOT_FOUND
	 * @throws BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED 
     */
    function setContentAction($id, BorhanContentResource $contentResource)
    {
		$dbFileAsset = FileAssetPeer::retrieveByPK($id);
		if (!$dbFileAsset)
			throw new BorhanAPIException(BorhanErrors::FILE_ASSET_ID_NOT_FOUND, $id);
		
		$kContentResource = $contentResource->toObject();
    	$this->attachContentResource($dbFileAsset, $kContentResource);
		
		$fileAsset = new BorhanFileAsset();
		$fileAsset->fromObject($dbFileAsset, $this->getResponseProfile());
		return $fileAsset;
    }
    
	/**
	 * @param FileAsset $dbFileAsset
	 * @param kContentResource $contentResource
	 * @throws BorhanErrors::UPLOAD_TOKEN_INVALID_STATUS_FOR_ADD_ENTRY
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN
	 * @throws BorhanErrors::RECORDED_WEBCAM_FILE_NOT_FOUND
	 * @throws BorhanErrors::FLAVOR_ASSET_ID_NOT_FOUND
	 * @throws BorhanErrors::STORAGE_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED
	 */
	protected function attachContentResource(FileAsset $dbFileAsset, kContentResource $contentResource)
	{
    	switch($contentResource->getType())
    	{
			case 'kLocalFileResource':
				return $this->attachLocalFileResource($dbFileAsset, $contentResource);
				
			case 'kFileSyncResource':
				return $this->attachFileSyncResource($dbFileAsset, $contentResource);
				
			default:
				$msg = "Resource of type [" . get_class($contentResource) . "] is not supported";
				BorhanLog::err($msg);
				
				throw new BorhanAPIException(BorhanErrors::RESOURCE_TYPE_NOT_SUPPORTED, get_class($contentResource));
    	}
    }
    
	/**
	 * @param FileAsset $dbFileAsset
	 * @param kLocalFileResource $contentResource
	 */
	protected function attachLocalFileResource(FileAsset $dbFileAsset, kLocalFileResource $contentResource)
	{
		if($contentResource->getIsReady())
			return $this->attachFile($dbFileAsset, $contentResource->getLocalFilePath(), $contentResource->getKeepOriginalFile());
			
		$dbFileAsset->setStatus(FileAssetStatus::UPLOADING);
		$dbFileAsset->save();
		
		$contentResource->attachCreatedObject($dbFileAsset);
    }
    
	/**
	 * @param FileAsset $dbFileAsset
	 * @param kFileSyncResource $contentResource
	 */
	protected function attachFileSyncResource(FileAsset $dbFileAsset, kFileSyncResource $contentResource)
	{
    	$syncable = kFileSyncObjectManager::retrieveObject($contentResource->getFileSyncObjectType(), $contentResource->getObjectId());
    	$srcSyncKey = $syncable->getSyncKey($contentResource->getObjectSubType(), $contentResource->getVersion());
    	
        return $this->attachFileSync($dbFileAsset, $srcSyncKey);
    }
    
	/**
	 * @param FileAsset $dbFileAsset
	 * @param string $fullPath
	 * @param bool $copyOnly
	 */
	protected function attachFile(FileAsset $dbFileAsset, $fullPath, $copyOnly = false)
	{
		if(!$dbFileAsset->getFileExt())
		{
			$ext = pathinfo($fullPath, PATHINFO_EXTENSION);
			$dbFileAsset->setFileExt($ext);
		}
		$dbFileAsset->setSize(kFile::fileSize($fullPath));
		$dbFileAsset->incrementVersion();
		$dbFileAsset->save();
		
		$syncKey = $dbFileAsset->getSyncKey(FileAsset::FILE_SYNC_ASSET);
		
		kFileSyncUtils::moveFromFile($fullPath, $syncKey, true, $copyOnly);
		
		$dbFileAsset->setStatus(FileAssetStatus::READY);
		$dbFileAsset->save();
    }
    
	/**
	 * @param FileAsset $dbFileAsset
	 * @param FileSyncKey $srcSyncKey
	 */
	protected function attachFileSync(FileAsset $dbFileAsset, FileSyncKey $srcSyncKey)
	{
		$dbFileAsset->incrementVersion();
		$dbFileAsset->save();
		
        $newSyncKey = $dbFileAsset->getSyncKey(FileAsset::FILE_SYNC_ASSET);
        kFileSyncUtils::createSyncFileLinkForKey($newSyncKey, $srcSyncKey);
                
        $fileSync = kFileSyncUtils::getLocalFileSyncForKey($newSyncKey, false);
        $fileSync = kFileSyncUtils::resolve($fileSync);
        
		$dbFileAsset->setStatus(FileAssetStatus::READY);
		$dbFileAsset->setSize($fileSync->getFileSize());
		$dbFileAsset->save();
    }
    
	/**
	 * List file assets by filter and pager
	 * 
	 * @action list
	 * @param BorhanFilterPager $filter
	 * @param BorhanFileAssetFilter $pager
	 * @return BorhanFileAssetListResponse
	 */
	function listAction(BorhanFileAssetFilter $filter, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanFileAssetFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());   
	}
}
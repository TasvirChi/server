<?php

/**
 * DropFolder service lets you create and manage drop folders
 * @service dropFolder
 * @package plugins.dropFolder
 * @subpackage api.services
 */
class DropFolderService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		if (!DropFolderPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, DropFolderPlugin::PLUGIN_NAME);
			
		$this->applyPartnerFilterForClass('DropFolder');
		$this->applyPartnerFilterForClass('DropFolderFile');
	}
		
	
	
	/**
	 * Allows you to add a new BorhanDropFolder object
	 * 
	 * @action add
	 * @param BorhanDropFolder $dropFolder
	 * @return BorhanDropFolder
	 * 
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 * @throws BorhanErrors::INGESTION_PROFILE_ID_NOT_FOUND
	 * @throws BorhanDropFolderErrors::DROP_FOLDER_ALREADY_EXISTS
	 * @throws BorhanErrors::DATA_CENTER_ID_NOT_FOUND
	 */
	public function addAction(BorhanDropFolder $dropFolder)
	{
		// check for required parameters
		$dropFolder->validatePropertyNotNull('name');
		$dropFolder->validatePropertyNotNull('status');
		$dropFolder->validatePropertyNotNull('type');
		$dropFolder->validatePropertyNotNull('dc');
		$dropFolder->validatePropertyNotNull('path');
		$dropFolder->validatePropertyNotNull('partnerId');
		$dropFolder->validatePropertyMinValue('fileSizeCheckInterval', 0, true);
		$dropFolder->validatePropertyMinValue('autoFileDeleteDays', 0, true);
		$dropFolder->validatePropertyNotNull('fileHandlerType');
		$dropFolder->validatePropertyNotNull('fileHandlerConfig');
		
		// validate values
		
		if (is_null($dropFolder->fileSizeCheckInterval)) {
			$dropFolder->fileSizeCheckInterval = DropFolder::FILE_SIZE_CHECK_INTERVAL_DEFAULT_VALUE;
		}
		
		if (is_null($dropFolder->fileNamePatterns)) {
			$dropFolder->fileNamePatterns = DropFolder::FILE_NAME_PATTERNS_DEFAULT_VALUE;
		}
		
		if (!kDataCenterMgr::dcExists($dropFolder->dc)) {
			throw new BorhanAPIException(BorhanErrors::DATA_CENTER_ID_NOT_FOUND, $dropFolder->dc);
		}
		
		if (!PartnerPeer::retrieveByPK($dropFolder->partnerId)) {
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $dropFolder->partnerId);
		}
		
		if (!DropFolderPlugin::isAllowedPartner($dropFolder->partnerId))
		{
			throw new BorhanAPIException(BorhanErrors::PLUGIN_NOT_AVAILABLE_FOR_PARTNER, DropFolderPlugin::getPluginName(), $dropFolder->partnerId);
		}

		if($dropFolder->type == BorhanDropFolderType::LOCAL)
		{
			$existingDropFolder = DropFolderPeer::retrieveByPathDefaultFilter($dropFolder->path);
			if ($existingDropFolder) {
				throw new BorhanAPIException(BorhanDropFolderErrors::DROP_FOLDER_ALREADY_EXISTS, $dropFolder->path);
			}
		}
		
		if (!is_null($dropFolder->conversionProfileId)) {
			$conversionProfileDb = conversionProfile2Peer::retrieveByPK($dropFolder->conversionProfileId);
			if (!$conversionProfileDb) {
				throw new BorhanAPIException(BorhanErrors::INGESTION_PROFILE_ID_NOT_FOUND, $dropFolder->conversionProfileId);
			}
		}
		
		// save in database
		$dbDropFolder = $dropFolder->toInsertableObject();
		$dbDropFolder->save();
		
		// return the saved object
		$dropFolder = BorhanDropFolder::getInstanceByType($dbDropFolder->getType());
		$dropFolder->fromObject($dbDropFolder, $this->getResponseProfile());
		return $dropFolder;
		
	}
	
	/**
	 * Retrieve a BorhanDropFolder object by ID
	 * 
	 * @action get
	 * @param int $dropFolderId 
	 * @return BorhanDropFolder
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function getAction($dropFolderId)
	{
		$dbDropFolder = DropFolderPeer::retrieveByPK($dropFolderId);
		
		if (!$dbDropFolder) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderId);
		}
			
		$dropFolder = BorhanDropFolder::getInstanceByType($dbDropFolder->getType());
		$dropFolder->fromObject($dbDropFolder, $this->getResponseProfile());
		
		return $dropFolder;
	}
	

	/**
	 * Update an existing BorhanDropFolder object
	 * 
	 * @action update
	 * @param int $dropFolderId
	 * @param BorhanDropFolder $dropFolder
	 * @return BorhanDropFolder
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 * @throws BorhanErrors::INGESTION_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::DATA_CENTER_ID_NOT_FOUND
	 */	
	public function updateAction($dropFolderId, BorhanDropFolder $dropFolder)
	{
		$dbDropFolder = DropFolderPeer::retrieveByPK($dropFolderId);
		
		if (!$dbDropFolder) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderId);
		}
		
		$dropFolder->validatePropertyMinValue('fileSizeCheckInterval', 0, true);
		$dropFolder->validatePropertyMinValue('autoFileDeleteDays', 0, true);
		
		if (!is_null($dropFolder->path) && $dropFolder->path != $dbDropFolder->getPath() && $dropFolder->type == BorhanDropFolderType::LOCAL) 
		{
			$existingDropFolder = DropFolderPeer::retrieveByPathDefaultFilter($dropFolder->path);
			if ($existingDropFolder) {
				throw new BorhanAPIException(BorhanDropFolderErrors::DROP_FOLDER_ALREADY_EXISTS, $dropFolder->path);
			}
		}
		
		if (!is_null($dropFolder->dc)) {
			if (!kDataCenterMgr::dcExists($dropFolder->dc)) {
				throw new BorhanAPIException(BorhanErrors::DATA_CENTER_ID_NOT_FOUND, $dropFolder->dc);
			}
		}
		
		if (!is_null($dropFolder->conversionProfileId)) {
			$conversionProfileDb = conversionProfile2Peer::retrieveByPK($dropFolder->conversionProfileId);
			if (!$conversionProfileDb) {
				throw new BorhanAPIException(BorhanErrors::INGESTION_PROFILE_ID_NOT_FOUND, $dropFolder->conversionProfileId);
			}
		}
					
		$dbDropFolder = $dropFolder->toUpdatableObject($dbDropFolder);
		$dbDropFolder->save();
	
		$dropFolder = BorhanDropFolder::getInstanceByType($dbDropFolder->getType());
		$dropFolder->fromObject($dbDropFolder, $this->getResponseProfile());
		
		return $dropFolder;
	}

	/**
	 * Mark the BorhanDropFolder object as deleted
	 * 
	 * @action delete
	 * @param int $dropFolderId 
	 * @return BorhanDropFolder
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function deleteAction($dropFolderId)
	{
		$dbDropFolder = DropFolderPeer::retrieveByPK($dropFolderId);
		
		if (!$dbDropFolder) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderId);
		}

		$dbDropFolder->setStatus(DropFolderStatus::DELETED);
		$dbDropFolder->save();
			
		$dropFolder = BorhanDropFolder::getInstanceByType($dbDropFolder->getType());
		$dropFolder->fromObject($dbDropFolder, $this->getResponseProfile());
		
		return $dropFolder;
	}
	
	/**
	 * List BorhanDropFolder objects
	 * 
	 * @action list
	 * @param BorhanDropFolderFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDropFolderListResponse
	 */
	public function listAction(BorhanDropFolderFilter  $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDropFolderFilter();
			
		$dropFolderFilter = $filter->toObject();

		$c = new Criteria();
		$dropFolderFilter->attachToCriteria($c);
		$count = DropFolderPeer::doCount($c);
		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = DropFolderPeer::doSelect($c);
		
		$response = new BorhanDropFolderListResponse();
		$response->objects = BorhanDropFolderArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
		
		return $response;
	}
	
}

<?php

/**
 * Data service lets you manage data content (textual content)
 *
 * @service data
 * @package api
 * @subpackage services
 */
class DataService extends BorhanEntryService
{
	
	protected function borhanNetworkAllowed($actionName)
	{
		if ($actionName === 'get') {
			return true;
		}
		return parent::borhanNetworkAllowed($actionName);
	}
	
	
	/**
	 * Adds a new data entry
	 * 
	 * @action add
	 * @param BorhanDataEntry $dataEntry Data entry
	 * @return BorhanDataEntry The new data entry
	 */
	function addAction(BorhanDataEntry $dataEntry)
	{
		$dbEntry = $dataEntry->toObject(new entry());
		
		$this->checkAndSetValidUserInsert($dataEntry, $dbEntry);
		$this->checkAdminOnlyInsertProperties($dataEntry);
		$this->validateAccessControlId($dataEntry);
		$this->validateEntryScheduleDates($dataEntry, $dbEntry);
		
		$dbEntry->setPartnerId($this->getPartnerId());
		$dbEntry->setSubpId($this->getPartnerId() * 100);
		$dbEntry->setStatus(BorhanEntryStatus::READY);
		$dbEntry->setMediaType(entry::ENTRY_MEDIA_TYPE_AUTOMATIC); 
		$dbEntry->save();
		
		$trackEntry = new TrackEntry();
		$trackEntry->setEntryId($dbEntry->getId());
		$trackEntry->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$trackEntry->setDescription(__METHOD__ . ":" . __LINE__ . "::ENTRY_DATA");
		TrackEntry::addTrackEntry($trackEntry);
		
		$dataEntry->fromObject($dbEntry, $this->getResponseProfile());
		
		myNotificationMgr::createNotification(kNotificationJobData::NOTIFICATION_TYPE_ENTRY_ADD, $dbEntry);
		
		return $dataEntry;
	}
	
	/**
	 * Get data entry by ID.
	 * 
	 * @action get
	 * @param string $entryId Data entry id
	 * @param int $version Desired version of the data
	 * @return BorhanDataEntry The requested data entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	function getAction($entryId, $version = -1)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);

		if (!$dbEntry || $dbEntry->getType() != BorhanEntryType::DATA)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		if ($version !== -1)
			$dbEntry->setDesiredVersion($version);
			
		$dataEntry = new BorhanDataEntry();
		$dataEntry->fromObject($dbEntry, $this->getResponseProfile());

		return $dataEntry;
	}
	
	/**
	 * Update data entry. Only the properties that were set will be updated.
	 * 
	 * @action update
	 * @param string $entryId Data entry id to update
	 * @param BorhanDataEntry $documentEntry Data entry metadata to update
	 * @return BorhanDataEntry The updated data entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * validateUser entry $entryId edit
	 */
	function updateAction($entryId, BorhanDataEntry $documentEntry)
	{
		return $this->updateEntry($entryId, $documentEntry, BorhanEntryType::DATA);
	}
	
	/**
	 * Delete a data entry.
	 *
	 * @action delete
	 * @param string $entryId Data entry id to delete
	 * 
 	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
 	 * @validateUser entry entryId edit
	 */
	function deleteAction($entryId)
	{
		$this->deleteEntry($entryId, BorhanEntryType::DATA);
	}
	
	/**
	 * List data entries by filter with paging support.
	 * 
	 * @action list
     * @param BorhanDataEntryFilter $filter Document entry filter
	 * @param BorhanFilterPager $pager Pager
	 * @return BorhanDataListResponse Wrapper for array of document entries and total count
	 */
	function listAction(BorhanDataEntryFilter $filter = null, BorhanFilterPager $pager = null)
	{
	    if (!$filter)
			$filter = new BorhanDataEntryFilter();
			
	    $filter->typeEqual = BorhanEntryType::DATA;
	    list($list, $totalCount) = parent::listEntriesByFilter($filter, $pager);
	    
	    $newList = BorhanDataEntryArray::fromDbArray($list, $this->getResponseProfile());
		$response = new BorhanDataListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		return $response;
	}
	
	/**
	 * serve action returan the file from dataContent field.
	 * 
	 * @action serve
	 * @param string $entryId Data entry id
	 * @param int $version Desired version of the data
	 * @param bool $forceProxy force to get the content without redirect
	 * @return file
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	function serveAction($entryId, $version = -1, $forceProxy = false)
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);

		if (!$dbEntry || $dbEntry->getType() != BorhanEntryType::DATA)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		$ksObj = $this->getKs();
		$ks = ($ksObj) ? $ksObj->getOriginalString() : null;
		$securyEntryHelper = new KSecureEntryHelper($dbEntry, $ks, null, ContextType::DOWNLOAD);
		$securyEntryHelper->validateForDownload();	
		
		if ( ! $version || $version == -1 ) $version = null;
		
		$fileName = $dbEntry->getName();
		
		$syncKey = $dbEntry->getSyncKey( entry::FILE_SYNC_ENTRY_SUB_TYPE_DATA , $version);
		list($fileSync, $local) = kFileSyncUtils::getReadyFileSyncForKey($syncKey, true, false);
		
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		
		if($local)
		{
			$filePath = $fileSync->getFullPath();
			$mimeType = kFile::mimeType($filePath);
			return $this->dumpFile($filePath, $mimeType);
		}
		else
		{
			$remoteUrl = kDataCenterMgr::getRedirectExternalUrl($fileSync);
			BorhanLog::info("Redirecting to [$remoteUrl]");
			if($forceProxy)
			{
				kFileUtils::dumpUrl($remoteUrl);
			}
			else
			{
				// or redirect if no proxy
				header("Location: $remoteUrl");
				die;
			}
		}	
	}
}

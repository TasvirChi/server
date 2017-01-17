<?php
/**
 * EmailIngestionProfile service lets you manage email ingestion profile records
 *
 * @service EmailIngestionProfile
 * @package api
 * @subpackage services
 */
class EmailIngestionProfileService extends BorhanEntryService
{
	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('category');
	}

	/**
	 * EmailIngestionProfile Add action allows you to add a EmailIngestionProfile to Borhan DB
	 *
	 * @action add
	 * @param BorhanEmailIngestionProfile $EmailIP Mandatory input parameter of type BorhanEmailIngestionProfile
	 * @return BorhanEmailIngestionProfile
	 *
	 * @throws BorhanErrors::EMAIL_INGESTION_PROFILE_EMAIL_EXISTS
	 */
	function addAction( BorhanEmailIngestionProfile $EmailIP )
	{
		$existingEIP = EmailIngestionProfilePeer::retrieveByEmailAddressNoFilter($EmailIP->emailAddress);
		if($existingEIP)
		{
			throw new BorhanAPIException(BorhanErrors::EMAIL_INGESTION_PROFILE_EMAIL_EXISTS, $EmailIP->emailAddress);
		}

		$dbEIP = $EmailIP->toInsertableObject();
		$dbEIP->setPartnerId ( $this->getPartnerId() );
		$dbEIP->save();

		$savedEIP = new BorhanEmailIngestionProfile(); // start from blank
		$savedEIP->fromObject($dbEIP, $this->getResponseProfile());

		return $savedEIP;
	}

	/**
	 * Retrieve a EmailIngestionProfile by email address
	 *
	 * @action getByEmailAddress
	 * @param string $emailAddress
	 * @return BorhanEmailIngestionProfile
	 *
	 * @throws BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND
	 */
	function getByEmailAddressAction($emailAddress)
	{
		$existingEIP = EmailIngestionProfilePeer::retrieveByEmailAddressNoFilter($emailAddress);
		if(!$existingEIP)
		throw new BorhanAPIException(BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND, $emailAddress);

		$emailIP = new BorhanEmailIngestionProfile();
		$emailIP->fromObject($existingEIP, $this->getResponseProfile());

		return $emailIP;
	}

	/**
	 * Retrieve a EmailIngestionProfile by id
	 *
	 * @action get
	 * @param int $id
	 * @return BorhanEmailIngestionProfile
	 *
	 * @throws BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND
	 */
	function getAction($id)
	{
		$existingEIP = EmailIngestionProfilePeer::retrieveByPK($id);
		if(!$existingEIP)
		throw new BorhanAPIException(BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND, $id);
			
		$emailIP = new BorhanEmailIngestionProfile();
		$emailIP->fromObject($existingEIP, $this->getResponseProfile());

		return $emailIP;
	}

	/**
	 * Update an existing EmailIngestionProfile
	 *
	 * @action update
	 * @param int $id
	 * @param BorhanEmailIngestionProfile $EmailIP
	 * @return BorhanEmailIngestionProfile
	 *
	 * @throws BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND
	 */
	function updateAction( $id , BorhanEmailIngestionProfile $EmailIP )
	{
		$dbEIP = EmailIngestionProfilePeer::retrieveByPK( $id );

		if ( ! $dbEIP )
			throw new BorhanAPIException ( BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND , $id );

		$EmailIP->emailAddress = $dbEIP->getEmailAddress();
		$updateEIP = $EmailIP->toUpdatableObject($dbEIP);

		$dbEIP->save();
		$updateEIP->fromObject($dbEIP, $this->getResponseProfile());

		return $updateEIP;
	}

	/**
	 * Delete an existing EmailIngestionProfile
	 *
	 * @action delete
	 * @param int $id
	 *
	 * @throws BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND
	 */
	function deleteAction( $id )
	{
		$dbEIP = EmailIngestionProfilePeer::retrieveByPK( $id );

		if ( ! $dbEIP )
		throw new BorhanAPIException ( BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND , $id );

		$dbEIP->setStatus ( EmailIngestionProfile::EMAIL_INGESTION_PROFILE_STATUS_INACTIVE );

		$dbEIP->save();
	}

	/**
	 * add BorhanMediaEntry from email ingestion
	 *
	 * @action addMediaEntry
	 * @param BorhanMediaEntry $mediaEntry Media entry metadata
	 * @param string $uploadTokenId Upload token id
	 * @param int $emailProfId
	 * @param string $fromAddress
	 * @param string $emailMsgId
	 *
	 * @return BorhanMediaEntry
	 *
	 * @throws BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN
	 * @throws BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND
	 *
	 */
	function addMediaEntryAction(BorhanMediaEntry $mediaEntry, $uploadTokenId, $emailProfId, $fromAddress, $emailMsgId)
	{
		try
	    {
	    	// check that the uploaded file exists
			$entryFullPath = kUploadTokenMgr::getFullPathByUploadTokenId($uploadTokenId);
			
			if (!file_exists($entryFullPath))
				throw new BorhanAPIException(BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN);
	
			// get the email profile by the given id
			$existingEIP = EmailIngestionProfilePeer::retrieveByPK($emailProfId);
			if(!$existingEIP)
			    throw new BorhanAPIException(BorhanErrors::EMAIL_INGESTION_PROFILE_NOT_FOUND, $emailProfId);
	
			$emailIP = new BorhanEmailIngestionProfile();
			$emailIP->fromObject($existingEIP, $this->getResponseProfile());
	
	
			// handle defaults for media entry metadata
			$this->changeIfNull($mediaEntry->tags,              	$emailIP->defaultTags);
			$this->changeIfNull($mediaEntry->adminTags,         	$emailIP->defaultAdminTags);
			$this->changeIfNull($mediaEntry->conversionProfileId,	$emailIP->conversionProfile2Id);
			$this->changeIfNull($mediaEntry->userId,            	$emailIP->defaultUserId);
			if ( is_null($mediaEntry->categories) || is_null(categoryPeer::getByFullNameExactMatch($mediaEntry->categories)) )  {
				$mediaEntry->categories = $emailIP->defaultCategory;
			}
	
	
			// validate the input object
			//$entry->validatePropertyMinLength("name", 1);
			if (!$mediaEntry->name)
			$mediaEntry->name = $this->getPartnerId().'_'.time();
	
			// first copy all the properties to the db entry, then we'll check for security stuff
			$dbEntry = $mediaEntry->toObject(new entry());
	
			if($emailIP->moderationStatus == BorhanEntryModerationStatus::PENDING_MODERATION)
			{
				$dbEntry->setModerate(true);
			}
	
			$dbEntry->setType(BorhanEntryType::MEDIA_CLIP);
			$dbEntry->setMediaType(entry::ENTRY_MEDIA_TYPE_AUTOMATIC);
	
			$this->checkAndSetValidUserInsert($mediaEntry, $dbEntry);
			$this->checkAdminOnlyInsertProperties($mediaEntry);
			$this->validateAccessControlId($mediaEntry);
			$this->validateEntryScheduleDates($mediaEntry, $dbEntry);
	
			$dbEntry->setPartnerId($this->getPartnerId());
			$dbEntry->setSubpId($this->getPartnerId() * 100);
			$dbEntry->setSourceId( $uploadTokenId );
			$dbEntry->setSourceLink( $entryFullPath );
			$dbEntry->setDefaultModerationStatus();
	
			$dbEntry->save();
	
			$te = new TrackEntry();
			$te->setEntryId( $dbEntry->getId() );
			$te->setTrackEventTypeId( TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY );
			$te->setDescription(  __METHOD__ . ":" . __LINE__ . "::ENTRY_MEDIA_SOURCE_EMAIL_INGESTION" );
			$te->setParam1Str($fromAddress);
			$te->setParam2Str($emailMsgId);
			$te->setParam3Str($emailProfId.'::'.$emailIP->emailAddress.'::'.$emailIP->mailboxId);
			TrackEntry::addTrackEntry( $te );
	
			$kshow = $this->createDummyKShow();
			$kshowId = $kshow->getId();
				
			myEntryUtils::setEntryTypeAndMediaTypeFromFile($dbEntry, $entryFullPath);
				
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
	
			$mediaEntry->fromObject($dbEntry, $this->getResponseProfile());
			return $mediaEntry;
	    }
	    catch(kCoreException $ex)
	    {
	    	if ($ex->getCode() == kUploadTokenException::UPLOAD_TOKEN_INVALID_STATUS);
	    		throw new BorhanAPIException(BorhanErrors::UPLOAD_TOKEN_INVALID_STATUS_FOR_ADD_ENTRY);
	    		
    		throw $ex;
	    }
	}


	private function changeIfNull(&$toChange, $from)
	{
		if ($toChange == null || !$toChange) {
			$toChange = $from;
		}
	}
}
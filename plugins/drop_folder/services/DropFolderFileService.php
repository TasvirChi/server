<?php

/**
 * DropFolderFile service lets you create and manage drop folder files
 * @service dropFolderFile
 * @package plugins.dropFolder
 * @subpackage api.services
 */
class DropFolderFileService extends BorhanBaseService
{
	const MYSQL_CODE_DUPLICATE_KEY = 23000;
	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		if (!DropFolderPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, DropFolderPlugin::PLUGIN_NAME);
		
		$this->applyPartnerFilterForClass('DropFolder');
		$this->applyPartnerFilterForClass('DropFolderFile');
	}
		
	/**
	 * Allows you to add a new BorhanDropFolderFile object
	 * 
	 * @action add
	 * @param BorhanDropFolderFile $dropFolderFile
	 * @return BorhanDropFolderFile
	 * 
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 * @throws BorhanDropFolderErrors::DROP_FOLDER_NOT_FOUND
	 */
	public function addAction(BorhanDropFolderFile $dropFolderFile)
	{
		return $this->newFileAddedOrDetected($dropFolderFile, DropFolderFileStatus::UPLOADING);
	}
	
	/**
	 * Retrieve a BorhanDropFolderFile object by ID
	 * 
	 * @action get
	 * @param int $dropFolderFileId 
	 * @return BorhanDropFolderFile
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function getAction($dropFolderFileId)
	{
		$dbDropFolderFile = DropFolderFilePeer::retrieveByPK($dropFolderFileId);
		
		if (!$dbDropFolderFile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderFileId);
		}
			
		$dropFolderFile = BorhanDropFolderFile::getInstanceByType($dbDropFolderFile->getType());
		$dropFolderFile->fromObject($dbDropFolderFile, $this->getResponseProfile());
		
		return $dropFolderFile;
	}
	

	/**
	 * Update an existing BorhanDropFolderFile object
	 * 
	 * @action update
	 * @param int $dropFolderFileId
	 * @param BorhanDropFolderFile $dropFolderFile
	 * @return BorhanDropFolderFile
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	public function updateAction($dropFolderFileId, BorhanDropFolderFile $dropFolderFile)
	{
		$dbDropFolderFile = DropFolderFilePeer::retrieveByPK($dropFolderFileId);
		
		if (!$dbDropFolderFile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderFileId);
		}
		
		if (!is_null($dropFolderFile->fileSize)) {
			$dropFolderFile->validatePropertyMinValue('fileSize', 0);
		}
					
		$dbDropFolderFile = $dropFolderFile->toUpdatableObject($dbDropFolderFile);
		$dbDropFolderFile->save();
	
		$dropFolderFile = new BorhanDropFolderFile();
		$dropFolderFile->fromObject($dbDropFolderFile, $this->getResponseProfile());
		
		return $dropFolderFile;
	}
	

	/**
	 * Update status of BorhanDropFolderFile
	 * 
	 * @action updateStatus
	 * @param int $dropFolderFileId
	 * @param BorhanDropFolderFileStatus $status
	 * @return BorhanDropFolderFile
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	public function updateStatusAction($dropFolderFileId, $status)
	{
		$dbDropFolderFile = DropFolderFilePeer::retrieveByPK($dropFolderFileId);
		if (!$dbDropFolderFile)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderFileId);
			
		if ($status != BorhanDropFolderFileStatus::PURGED && $dbDropFolderFile->getStatus() == BorhanDropFolderFileStatus::DELETED)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderFileId);
		
		$dbDropFolderFile->setStatus($status);
		$dbDropFolderFile->save();
	
		$dropFolderFile = BorhanDropFolderFile::getInstanceByType($dbDropFolderFile->getType());
		$dropFolderFile->fromObject($dbDropFolderFile, $this->getResponseProfile());
		
		return $dropFolderFile;
	}

	/**
	 * Mark the BorhanDropFolderFile object as deleted
	 * 
	 * @action delete
	 * @param int $dropFolderFileId 
	 * @return BorhanDropFolderFile
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function deleteAction($dropFolderFileId)
	{
		$dbDropFolderFile = DropFolderFilePeer::retrieveByPK($dropFolderFileId);
		
		if (!$dbDropFolderFile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderFileId);
		}
		
		$dbDropFolderFile->setStatus(DropFolderFileStatus::DELETED);
		$dbDropFolderFile->save();
			
		$dropFolderFile = BorhanDropFolderFile::getInstanceByType($dbDropFolderFile->getType());
		$dropFolderFile->fromObject($dbDropFolderFile, $this->getResponseProfile());
		
		return $dropFolderFile;
	}
	
	/**
	 * List BorhanDropFolderFile objects
	 * 
	 * @action list
	 * @param BorhanDropFolderFileFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDropFolderFileListResponse
	 */
	public function listAction(BorhanDropFolderFileFilter  $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDropFolderFileFilter();
			
		$dropFolderFileFilter = $filter->toObject();

		$c = new Criteria();
		$dropFolderFileFilter->attachToCriteria($c);		
		$count = DropFolderFilePeer::doCount($c);
		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = DropFolderFilePeer::doSelect($c);
		
		$response = new BorhanDropFolderFileListResponse();
		$response->objects = BorhanDropFolderFileArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
		
		return $response;
	}
	
	
	/**
	 * Set the BorhanDropFolderFile status to ignore (BorhanDropFolderFileStatus::IGNORE)
	 * 
	 * @action ignore
	 * @param int $dropFolderFileId 
	 * @return BorhanDropFolderFile
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function ignoreAction($dropFolderFileId)
	{
		$dbDropFolderFile = DropFolderFilePeer::retrieveByPK($dropFolderFileId);
		
		if (!$dbDropFolderFile) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $dropFolderFileId);
		}

		$dbDropFolderFile->setStatus(DropFolderFileStatus::IGNORE);
		$dbDropFolderFile->save();
			
		$dropFolderFile = new BorhanDropFolderFile();
		$dropFolderFile->fromObject($dbDropFolderFile, $this->getResponseProfile());
		
		return $dropFolderFile;
	}
	
	private function newFileAddedOrDetected(BorhanDropFolderFile $dropFolderFile, $fileStatus)
	{
		// check for required parameters
		$dropFolderFile->validatePropertyNotNull('dropFolderId');
		$dropFolderFile->validatePropertyNotNull('fileName');
		$dropFolderFile->validatePropertyMinValue('fileSize', 0);
		
		// check that drop folder id exists in the system
		$dropFolder = DropFolderPeer::retrieveByPK($dropFolderFile->dropFolderId);
		if (!$dropFolder) {
			throw new BorhanAPIException(BorhanDropFolderErrors::DROP_FOLDER_NOT_FOUND, $dropFolderFile->dropFolderId);
		}
				
		// save in database
		$dropFolderFile->status = null;		
		$dbDropFolderFile = $dropFolderFile->toInsertableObject();
		/* @var $dbDropFolderFile DropFolderFile  */
		$dbDropFolderFile->setPartnerId($dropFolder->getPartnerId());
		$dbDropFolderFile->setStatus($fileStatus);
		$dbDropFolderFile->setType($dropFolder->getType());
		try 
		{
			$dbDropFolderFile->save();	
		}
		catch(PropelException $e)
		{
			if($e->getCause() && $e->getCause()->getCode() == self::MYSQL_CODE_DUPLICATE_KEY) //unique constraint
			{
				$existingDropFolderFile = DropFolderFilePeer::retrieveByDropFolderIdAndFileName($dropFolderFile->dropFolderId, $dropFolderFile->fileName);
				switch($existingDropFolderFile->getStatus())
				{					
					case DropFolderFileStatus::PARSED:
						BorhanLog::info('Exisiting file status is PARSED, updating status to ['.$fileStatus.']');
						$existingDropFolderFile = $dropFolderFile->toUpdatableObject($existingDropFolderFile);
						$existingDropFolderFile->setStatus($fileStatus);						
						$existingDropFolderFile->save();
						$dbDropFolderFile = $existingDropFolderFile;
						break;
					case DropFolderFileStatus::DETECTED:
						BorhanLog::info('Exisiting file status is DETECTED, updating status to ['.$fileStatus.']');
						$existingDropFolderFile = $dropFolderFile->toUpdatableObject($existingDropFolderFile);
						if($existingDropFolderFile->getStatus() != $fileStatus)
							$existingDropFolderFile->setStatus($fileStatus);
						$existingDropFolderFile->save();
						$dbDropFolderFile = $existingDropFolderFile;
						break;
					case DropFolderFileStatus::UPLOADING:
						if($fileStatus == DropFolderFileStatus::UPLOADING)
						{
							BorhanLog::log('Exisiting file status is UPLOADING, updating properties');
							$existingDropFolderFile = $dropFolderFile->toUpdatableObject($existingDropFolderFile);
							$existingDropFolderFile->save();
							$dbDropFolderFile = $existingDropFolderFile;
							break;							
						}
					default:
						BorhanLog::log('Setting current file to PURGED ['.$existingDropFolderFile->getId().']');
						$existingDropFolderFile->setStatus(DropFolderFileStatus::PURGED);				
						$existingDropFolderFile->save();
						
						$newDropFolderFile = $dbDropFolderFile->copy();
						if(	$existingDropFolderFile->getLeadDropFolderFileId() && 
							$existingDropFolderFile->getLeadDropFolderFileId() != $existingDropFolderFile->getId())
						{
							BorhanLog::info('Updating lead id ['.$existingDropFolderFile->getLeadDropFolderFileId().']');							
							$newDropFolderFile->setLeadDropFolderFileId($existingDropFolderFile->getLeadDropFolderFileId());	
						}
						$newDropFolderFile->save();
						$dbDropFolderFile = $newDropFolderFile;
				}
			}
			else 
			{
				throw $e;
			}
		}	
		// return the saved object
		$dropFolderFile = BorhanDropFolderFile::getInstanceByType($dbDropFolderFile->getType());
		$dropFolderFile->fromObject($dbDropFolderFile, $this->getResponseProfile());
		return $dropFolderFile;		
		
	}
	
}

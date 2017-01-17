<?php
/**
 * Watches drop folder files and executes file handlers as required 
 *
 * @package plugins.dropFolder
 * @subpackage Scheduler
 */
class KAsyncDropFolderWatcher extends KPeriodicWorker
{
	/**
	 * @var BorhanDropFolderClientPlugin
	 */
	protected $dropFolderPlugin = null;
	
			
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::DROP_FOLDER_WATCHER;
	}
	
	/* (non-PHPdoc)
	 * @see KBatchBase::run()
	*/
	public function run($jobs = null)
	{
		$this->dropFolderPlugin = BorhanDropFolderClientPlugin::get(self::$kClient);
		
		if(self::$taskConfig->isInitOnly())
			return $this->init();
		
		$dropFolders = $this->getDropFoldersList();
		if(isset($dropFolders))
		{
			$dropFolders = $dropFolders->objects;
			BorhanLog::log('['.count($dropFolders).'] folders to watch');
			
			foreach ($dropFolders as $folder)
			{
				/* @var $folder BorhanDropFolder */
			    try 
			    {	
			    	$this->impersonate($folder->partnerId);	
					$engine = KDropFolderEngine::getInstance($folder->type);			    	
				    $engine->watchFolder($folder);					    
				    $this->setDropFolderOK($folder);		
					$this->unimpersonate();					    
			    }
			    catch (kFileTransferMgrException $e)
			    {
			    	if($e->getCode() == kFileTransferMgrException::cantConnect)
			    		$this->setDropFolderError($folder, BorhanDropFolderErrorCode::ERROR_CONNECT, DropFolderPlugin::ERROR_CONNECT_MESSAGE, $e);
			    	else if($e->getCode() == kFileTransferMgrException::cantAuthenticate)
			    		$this->setDropFolderError($folder, BorhanDropFolderErrorCode::ERROR_AUTENTICATE, DropFolderPlugin::ERROR_AUTENTICATE_MESSAGE, $e);
			    	else
			    		$this->setDropFolderError($folder, BorhanDropFolderErrorCode::ERROR_GET_PHISICAL_FILE_LIST, DropFolderPlugin::ERROR_GET_PHISICAL_FILE_LIST_MESSAGE, $e);
			    	$this->unimpersonate();
			    }
			    catch (BorhanException $e)
			    {
			    	$this->setDropFolderError($folder, BorhanDropFolderErrorCode::ERROR_GET_DB_FILE_LIST, DropFolderPlugin::ERROR_GET_DB_FILE_LIST_MESSAGE, $e);
			    	$this->unimpersonate();
			    }
			    catch (Exception $e) 
			    {			        
			        $this->setDropFolderError($folder, BorhanDropFolderErrorCode::DROP_FOLDER_APP_ERROR, DropFolderPlugin::DROP_FOLDER_APP_ERROR_MESSAGE.$e->getMessage(), $e);	
			        $this->unimpersonate();
			    }
			}
		}
	}
	
		
	private function getDropFoldersList() 
	{
		$folderTags = self::$taskConfig->params->tags;
		
		if (strlen($folderTags) == 0) {		
			BorhanLog::err('Tags configuration is empty - cannot continue');			
			return null;
		}
		
		// get list of drop folders according to configuration
		$filter = new BorhanDropFolderFilter();
		
		if ($folderTags != '*') {
			$filter->tagsMultiLikeOr = $folderTags;
		}
			
		$filter->currentDc = BorhanNullableBoolean::TRUE_VALUE;
		$filter->statusIn = BorhanDropFolderStatus::ENABLED. ','. BorhanDropFolderStatus::ERROR;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 500;
		if(self::$taskConfig->params->pageSize)
			$pager->pageSize = self::$taskConfig->params->pageSize;	
		
		
		try 
		{
			$dropFolders = $this->dropFolderPlugin->dropFolder->listAction($filter, $pager);
			return $dropFolders;
		}
		catch (Exception $e) 
		{
			BorhanLog::err('Cannot get drop folder list - '.$e->getMessage());
			return null;
		}
	}
	
	private function setDropFolderError(BorhanDropFolder $folder, $errorCode, $errorDescirption, Exception $e)
	{
		BorhanLog::err('Error with folder id ['.$folder->id.'] - '.$e->getMessage());
		try 
		{
			$folder->status = BorhanDropFolderStatus::ERROR;
			$updateDropFolder = new BorhanDropFolder();
			$updateDropFolder->status = BorhanDropFolderStatus::ERROR;
			$updateDropFolder->errorCode = $errorCode;
			$updateDropFolder->errorDescription = $errorDescirption;
			$updateDropFolder->lastAccessedAt = time();
			
    		$this->dropFolderPlugin->dropFolder->update($folder->id, $updateDropFolder);
		}
		catch(Exception $e)
		{
			BorhanLog::err('Error updating drop folder ['.$folder->id.'] - '.$e->getMessage());
		}	
	}	
	
	private function setDropFolderOK(BorhanDropFolder $folder)
	{
		try 
		{
			$updateDropFolder = new BorhanDropFolder();
			$updateDropFolder->status = BorhanDropFolderStatus::ENABLED;
			$updateDropFolder->errorCode__null = '';
			$updateDropFolder->errorDescription__null = '';
			$updateDropFolder->lastAccessedAt = time();
				
	    	$this->dropFolderPlugin->dropFolder->update($folder->id, $updateDropFolder);
		}
		catch(Exception $e)
		{
			BorhanLog::err('Error updating drop folder ['.$folder->id.'] - '.$e->getMessage());
		}	
	}	
			
	function log($message)
	{
		if(!strstr($message, 'BorhanDropFolderListResponse') && !strstr($message, 'BorhanDropFolderFileListResponse'))
			BorhanLog::info($message);
	}	
}

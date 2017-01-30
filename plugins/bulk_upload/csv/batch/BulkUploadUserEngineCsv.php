<?php
/**
 * Class which parses the bulk upload CSV and creates the objects listed in it. 
 * This engine class parses CSVs which describe users.
 * 
 * @package plugins.bulkUploadCsv
 * @subpackage batch
 */
class BulkUploadUserEngineCsv extends BulkUploadEngineCsv
{
    const OBJECT_TYPE_TITLE = 'user';
     
    /**
     * (non-PHPdoc)
     * @see BulkUploadGeneralEngineCsv::createUploadResult()
     */
    protected function createUploadResult($values, $columns)
	{
		$bulkUploadResult = parent::createUploadResult($values, $columns);
		if (!$bulkUploadResult)
			return;
		
		$bulkUploadResult->bulkUploadResultObjectType = BorhanBulkUploadObjectType::USER;		 
				
		// trim the values
		array_walk($values, array('BulkUploadUserEngineCsv', 'trimArray'));
		
		// sets the result values
		$dateOfBirth = null;
		
		foreach($columns as $index => $column)
		{
			if(!is_numeric($index))
				continue;
            
			if ($column == 'dateOfBirth')
			{
			    $dateOfBirth = $values[$index];
			}
				
			if(iconv_strlen($values[$index], 'UTF-8'))
			{
				$bulkUploadResult->$column = $values[$index];
				BorhanLog::info("Set value $column [{$bulkUploadResult->$column}]");
			}
			else
			{
				BorhanLog::info("Value $column is empty");
			}
		}
		
		if(isset($columns['plugins']))
		{
			$bulkUploadPlugins = array();
			
			foreach($columns['plugins'] as $index => $column)
			{
				$bulkUploadPlugin = new BorhanBulkUploadPluginData();
				$bulkUploadPlugin->field = $column;
				$bulkUploadPlugin->value = iconv_strlen($values[$index], 'UTF-8') ? $values[$index] : null;
				$bulkUploadPlugins[] = $bulkUploadPlugin;
				
				BorhanLog::info("Set plugin value $column [{$bulkUploadPlugin->value}]");
			}
			
			$bulkUploadResult->pluginsData = $bulkUploadPlugins;
		}
		
		$bulkUploadResult->objectStatus = BorhanUserStatus::ACTIVE;
		$bulkUploadResult->status = BorhanBulkUploadResultStatus::IN_PROGRESS;
		
		if (!$bulkUploadResult->action)
		{
		    $bulkUploadResult->action = BorhanBulkUploadAction::ADD;
		}
		
		$bulkUploadResult = $this->validateBulkUploadResult($bulkUploadResult, $dateOfBirth);
		if($bulkUploadResult)
			$this->bulkUploadResults[] = $bulkUploadResult;
	}
    
	protected function validateBulkUploadResult (BorhanBulkUploadResult $bulkUploadResult, $dateOfBirth = null)
	{
	    /* @var $bulkUploadResult BorhanBulkUploadResultUser */
		if (!$bulkUploadResult->userId)
		{
		    $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
			$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
			$bulkUploadResult->errorDescription = "Mandatory Column [userId] missing from CSV.";
		}
		
		if ($dateOfBirth && !self::isFormatedDate($dateOfBirth, true))
		{
		    $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
			$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
			$bulkUploadResult->errorDescription = "Format of property dateOfBirth is incorrect [$dateOfBirth].";
		}
		
		if ($bulkUploadResult->gender && !self::isValidEnumValue("BorhanGender", $bulkUploadResult->gender))
		{
		    $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
			$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
			$bulkUploadResult->errorDescription = "Wrong value passed for property gender [$bulkUploadResult->gender]";
		}
		
	    if ($bulkUploadResult->action == BorhanBulkUploadAction::ADD_OR_UPDATE)
		{
		    KBatchBase::impersonate($this->currentPartnerId);;
		    try 
		    {
		        $user = KBatchBase::$kClient->user->get($bulkUploadResult->userId);
    		    if ( $user )
    		    {
    		        $bulkUploadResult->action = BorhanBulkUploadAction::UPDATE;
    		    }
		    }
	        catch (Exception $e)
	        {
	            $bulkUploadResult->action = BorhanBulkUploadAction::ADD;
		    }
		    KBatchBase::unimpersonate();
		}
		

		if($this->maxRecords && $this->lineNumber > $this->maxRecords) // check max records
		{
			$bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
			$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
			$bulkUploadResult->errorDescription = "Exeeded max records count per bulk";
		}
		
		if($bulkUploadResult->status == BorhanBulkUploadResultStatus::ERROR)
		{
			$this->addBulkUploadResult($bulkUploadResult);
			return null;
		}	
		
		$bulkUploadResult->dateOfBirth = self::parseFormatedDate($bulkUploadResult->dateOfBirth, true); 
		
		return $bulkUploadResult;
	}
	
	
    protected function addBulkUploadResult(BorhanBulkUploadResult $bulkUploadResult)
	{
		parent::addBulkUploadResult($bulkUploadResult);
		
	}
	/**
	 * 
	 * Create the entries from the given bulk upload results
	 */
	protected function createObjects()
	{
		// start a multi request for add entries
		KBatchBase::$kClient->startMultiRequest();
		
		BorhanLog::info("job[{$this->job->id}] start creating users");
		$bulkUploadResultChunk = array(); // store the results of the created entries
				
		
		foreach($this->bulkUploadResults as $bulkUploadResult)
		{
			/* @var $bulkUploadResult BorhanBulkUploadResultUser */
		    BorhanLog::info("Handling bulk upload result: [". $bulkUploadResult->userId ."]");
		    switch ($bulkUploadResult->action)
		    {
		        case BorhanBulkUploadAction::ADD:
    		        $user = $this->createUserFromResultAndJobData($bulkUploadResult);
        					
        			$bulkUploadResultChunk[] = $bulkUploadResult;
        			
        			KBatchBase::impersonate($this->currentPartnerId);;
        			KBatchBase::$kClient->user->add($user);
        			KBatchBase::unimpersonate();
        			
		            break;
		        
		        case BorhanBulkUploadAction::UPDATE:
		            $category = $this->createUserFromResultAndJobData($bulkUploadResult);
        					
        			$bulkUploadResultChunk[] = $bulkUploadResult;
        			
        			KBatchBase::impersonate($this->currentPartnerId);;
        			KBatchBase::$kClient->user->update($bulkUploadResult->userId, $category);
        			KBatchBase::unimpersonate();
        			
        			
		            break;
		            
		        case BorhanBulkUploadAction::DELETE:
		            $bulkUploadResultChunk[] = $bulkUploadResult;
        			
        			KBatchBase::impersonate($this->currentPartnerId);;
        			KBatchBase::$kClient->user->delete($bulkUploadResult->userId);
        			KBatchBase::unimpersonate();
        			
		            break;
		        
		        default:
		            $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
		            $bulkUploadResult->errorDescription = "Unknown action passed: [".$bulkUploadResult->action ."]";
		            break;
		    }
		    
		    if(KBatchBase::$kClient->getMultiRequestQueueSize() >= $this->multiRequestSize)
			{
				// make all the media->add as the partner
				$requestResults = KBatchBase::$kClient->doMultiRequest();
				
				$this->updateObjectsResults($requestResults, $bulkUploadResultChunk);
				$this->checkAborted();
				KBatchBase::$kClient->startMultiRequest();
				$bulkUploadResultChunk = array();
			}
		}
		
		// make all the category actions as the partner
		$requestResults = KBatchBase::$kClient->doMultiRequest();
		
		if(count($requestResults))
			$this->updateObjectsResults($requestResults, $bulkUploadResultChunk);

		BorhanLog::info("job[{$this->job->id}] finish modifying users");
	}
	
	/**
	 * Function to create a new user from bulk upload result.
	 * @param BorhanBulkUploadResultUser $bulkUploadUserResult
	 */
	protected function createUserFromResultAndJobData (BorhanBulkUploadResultUser $bulkUploadUserResult)
	{
	    $user = new BorhanUser();
	    //Prepare object
	    if ($bulkUploadUserResult->userId)
	        $user->id = $bulkUploadUserResult->userId;
	    
	    if ($bulkUploadUserResult->screenName)
	        $user->screenName = $bulkUploadUserResult->screenName;
	        
	    if ($bulkUploadUserResult->tags)
	        $user->tags = $bulkUploadUserResult->tags;
	        
	    if ($bulkUploadUserResult->firstName)
	        $user->firstName = $bulkUploadUserResult->firstName;
	        
	    if ($bulkUploadUserResult->lastName)
	        $user->lastName = $bulkUploadUserResult->lastName; 
	           
	    if ($bulkUploadUserResult->email)
	        $user->email = $bulkUploadUserResult->email;

	    if ($bulkUploadUserResult->city)
	        $user->city = $bulkUploadUserResult->city;
	        
	    if ($bulkUploadUserResult->country)
	        $user->country = $bulkUploadUserResult->country;
	        
	    if ($bulkUploadUserResult->state)
	        $user->state = $bulkUploadUserResult->state;
	    
	    if ($bulkUploadUserResult->zip)
	        $user->zip = $bulkUploadUserResult->zip; 
	        
	    if ($bulkUploadUserResult->gender)
	        $user->gender = $bulkUploadUserResult->gender; 
	    
	    if ($bulkUploadUserResult->dateOfBirth)
	        $user->dateOfBirth = $bulkUploadUserResult->dateOfBirth; 
	      
	    if ($bulkUploadUserResult->partnerData)
	        $user->partnerData = $bulkUploadUserResult->partnerData;

	    return $user;
	}
	
	/**
	 * 
	 * Gets the columns for V1 csv file
	 */
	protected function getColumns()
	{
		return array(
		    "action",
		    "userId",
		    "screenName",
		    "firstName",
		    "lastName",
		    "email",
		    "tags",
		    "gender",
		    "zip",
		    "country",
		    "state",
			"city",
		    "dateOfBirth",
			"partnerData",
			"group",
		);
	}
	
	
    protected function updateObjectsResults($requestResults, $bulkUploadResults)
	{
		BorhanLog::info("Updating " . count($requestResults) . " results");
		
		// checking the created entries
		foreach($requestResults as $index => $requestResult)
		{
			$bulkUploadResult = $bulkUploadResults[$index];
			
			if(is_array($requestResult) && isset($requestResult['code']))
			{
			    $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
				$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::BORHAN_API;
				$bulkUploadResult->objectStatus = $requestResult['code'];
				$bulkUploadResult->errorDescription = $requestResult['message'];
				$this->addBulkUploadResult($bulkUploadResult);
				continue;
			}
			
			if($requestResult instanceof Exception)
			{
				$bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
				$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::BORHAN_API;
				$bulkUploadResult->errorDescription = $requestResult->getMessage();
				$this->addBulkUploadResult($bulkUploadResult);
				continue;
			}
			
			if ($bulkUploadResult->action != BorhanBulkUploadAction::DELETE)
				$bulkUploadResult = $this->handleGroupUser($bulkUploadResult);
			$this->addBulkUploadResult($bulkUploadResult);
		}
	}

	protected function handleGroupUser (BorhanBulkUploadResultUser $userResult)
	{
		KBatchBase::impersonate($this->currentPartnerId);
		BorhanLog::info("Handling user/group association");
			
		$group = $userResult->group;
		if (strpos($group, "-") === 0)
		{
			//if the user is being removed from the group
			$group = substr($group, 1);
			if ($userResult->action == BorhanBulkUploadAction::ADD)
			{
				BorhanLog::info("Cannot remove user from group - user could not have belonged to the group");
				$userResult->errorCode = BorhanBatchJobErrorTypes::BORHAN_API;
				$userResult->errorDescription = "Cannot remove user from group - user could not have belonged to the group";
				KBatchBase::unimpersonate();
				
				return $userResult;
			}
			
			try {
				$removeResult = KBatchBase::$kClient->groupUser->delete ($userResult->userId, $group);
			}
			catch (Exception $e)
			{
				$userResult->errorCode = BorhanBatchJobErrorTypes::BORHAN_API;
				$userResult->errorDescription = $e->getMessage();
			}
			
		}
		else {
			try {
				$groupUser = KBatchBase::$kClient->user->get ($group);
				if ($groupUser->type != BorhanUserType::GROUP)
				{
					throw new Exception("The user exists but is not a group",1);
					
				}
			}
			catch (Exception $e)
			{
				BorhanLog::info ("The user cannot be added to group - group $group needs to be created");
				$groupUser = new BorhanUser();
				$groupUser->id = $group;
				$groupUser->type = BorhanUserType::GROUP;
				
				try {
					$groupUser = KBatchBase::$kClient->user->add($groupUser);
				}
				
				catch (Exception $e)
				{
					BorhanLog::info ("Unable to add non-existent group $group as a group user!");
					BorhanLog::info ("Error occurred: " . $e->getMessage());
					$userResult->errorCode = BorhanBatchJobErrorTypes::BORHAN_API;
					$userResult->errorDescription = "Group $group could not be created!";
					KBatchBase::unimpersonate();
					
					return $userResult;
				}
			}
			
			try{
				$groupUser = new BorhanGroupUser();
				$groupUser->userId = $userResult->userId;
				$groupUser->groupId = $userResult->group;
				
				$groupUserAssoc = KBatchBase::$kClient->groupUser->add($groupUser);
			}
			catch (Exception $e)
			{
				BorhanLog::info ("Error occurred: " . $e->getMessage());
				$userResult->errorCode = BorhanBatchJobErrorTypes::BORHAN_API;
				$userResult->errorDescription = $e->getMessage();
			}
		}		
		KBatchBase::unimpersonate();
		
		return $userResult;
	}
	
	protected function getUploadResultInstance ()
	{
	    return new BorhanBulkUploadResultUser();
	}
	
	public function getObjectTypeTitle()
	{
		return self::OBJECT_TYPE_TITLE;
	}
}
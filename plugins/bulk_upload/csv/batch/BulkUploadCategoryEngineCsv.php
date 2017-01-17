<?php
/**
 * Class which parses the bulk upload CSV and creates the objects listed in it. 
 * This engine class parses CSVs which describe categories.
 * 
 * @package plugins.bulkUploadCsv
 * @subpackage batch
 */
class BulkUploadCategoryEngineCsv extends BulkUploadEngineCsv
{
    const OBJECT_TYPE_TITLE = 'category';
    
    protected $mapFullNameToId = array();
    
    
    /* (non-PHPdoc)
	 * @see KBulkUploadEngine::handleBulkUpload()
	 */
	public function handleBulkUpload()
	{
		$openedEntries = KBatchBase::$kClient->batch->updateBulkUploadResults($this->job->id);
		//we will wait for in progress category items because there might be dependencies between the category bulk items.
		if($openedEntries)
		{
			BorhanLog::info("Some earlier category uploads are still in progress.");
			//will make the worker to restart the job.
			$this->exceededMaxRecordsEachRun = true;
			return;
		}
		parent::handleBulkUpload();
	}
    
    /**
     * (non-PHPdoc)
     * @see BulkUploadGeneralEngineCsv::createUploadResult()
     */
    protected function createUploadResult($values, $columns)
	{
		$bulkUploadResult = parent::createUploadResult($values, $columns);
		if (!$bulkUploadResult)
			return;
			
		$bulkUploadResult->bulkUploadResultObjectType = BorhanBulkUploadObjectType::CATEGORY;
			 
		// trim the values
		array_walk($values, array('BulkUploadCategoryEngineCsv', 'trimArray'));
		
		// sets the result values
		foreach($columns as $index => $column)
		{
			if(!is_numeric($index))
				continue;
            
			if ($column == 'categoryId')
			{
			    $bulkUploadResult->objectId = $values[$index];
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
		
		$bulkUploadResult->objectStatus = BorhanCategoryStatus::ACTIVE;
		$bulkUploadResult->status = BorhanBulkUploadResultStatus::IN_PROGRESS;
		
		if (!$bulkUploadResult->action)
		{
		    $bulkUploadResult->action = BorhanBulkUploadAction::ADD;
		}
		
		$bulkUploadResult = $this->validateBulkUploadResult($bulkUploadResult);
		
		$this->bulkUploadResults[] = $bulkUploadResult;
	}
    
	protected function validateBulkUploadResult (BorhanBulkUploadResult $bulkUploadResult)
	{
	    if ($bulkUploadResult->action == BorhanBulkUploadAction::ADD_OR_UPDATE)
		{
		    if ( $bulkUploadResult->objectId || $bulkUploadResult->referenceId)
		    {
		        KBatchBase::impersonate($this->currentPartnerId);
		        $bulkUploadResult->objectId = $this->calculateIdToUpdate($bulkUploadResult);
		        KBatchBase::unimpersonate();
		        if ($bulkUploadResult->objectId)
		        {
		            $bulkUploadResult->action = BorhanBulkUploadAction::UPDATE;
		        }
		        else
		        {
		            $bulkUploadResult->action = BorhanBulkUploadAction::ADD;
		        }
		    }
		    else 
		    {
		        $bulkUploadResult->action = BorhanBulkUploadAction::ADD;
		    }
		}
		
		switch ($bulkUploadResult->action)
		{
		    case BorhanBulkUploadAction::ADD:
        		if( !$bulkUploadResult->name )
        		{
        			$bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
        			$bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
        			$bulkUploadResult->errorDescription = "Mandatory Column [name] missing from CSV.";
        		}
        			
		        break;
		       
		    case BorhanBulkUploadAction::UPDATE:
        		if (!$bulkUploadResult->objectId && !$bulkUploadResult->referenceId)
    		    {
    		        $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
    			    $bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
    			    $bulkUploadResult->errorDescription = "Mandatory parameters missing for action [".$bulkUploadResult->action ."] - categoryId/referenceId";
    		    }
		        break;
		    
		    case BorhanBulkUploadAction::DELETE:
		        if (!$bulkUploadResult->objectId && !$bulkUploadResult->referenceId)
    		    {
    		        $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
    			    $bulkUploadResult->errorType = BorhanBatchJobErrorTypes::APP;
    			    $bulkUploadResult->errorDescription = "Mandatory parameters missing for action [".$bulkUploadResult->action ."]";
    		    }
		        break;
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
			return;
		}	
		
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
		// Because the bulk upload feature may be used to construct a category tree, we are unable to work with an ordinary multi-request.
		$requestResults = array();
		BorhanLog::info("job[{$this->job->id}] start creating categories");
		$bulkUploadResultChunk = array(); // store the results of the created entries
				
		
		KBatchBase::impersonate($this->currentPartnerId);;
		foreach($this->bulkUploadResults as $bulkUploadResult)
		{
			/* @var $bulkUploadResult BorhanBulkUploadResultCategory */
		    BorhanLog::info("Handling bulk upload result: [". $bulkUploadResult->name ."]");
		    try 
		    {
    		    switch ($bulkUploadResult->action)
    		    {
    		        case BorhanBulkUploadAction::ADD:
            			$bulkUploadResultChunk[] = $bulkUploadResult;
        		        $category = $this->createCategoryFromResultAndJobData($bulkUploadResult);
                		$requestResults[] = KBatchBase::$kClient->category->add($category);
 
    		            break;
    		        
    		        case BorhanBulkUploadAction::UPDATE:
    		            $bulkUploadResult->objectId = $this->calculateIdToUpdate($bulkUploadResult);
    		            if (is_null($bulkUploadResult->objectId))
    		            {
    		                $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
    		                $bulkUploadResult->errorDescription = "Category reference ID not found under the provided relativePath";
    		                KBatchBase::unimpersonate();
    		                try {
    		                    $this->addBulkUploadResult($bulkUploadResult);
    		                    KBatchBase::impersonate($this->currentPartnerId);;
    		                }
    		                catch (Exception $e)
    		                {
    		                    KBatchBase::impersonate($this->currentPartnerId);;
    		                }
    		                break;
    		            }
            			$bulkUploadResultChunk[] = $bulkUploadResult;
    		            $category = $this->createCategoryFromResultAndJobData($bulkUploadResult);
                		$requestResults[] = KBatchBase::$kClient->category->update($bulkUploadResult->objectId, $category);
    		            break;
    		            
    		        case BorhanBulkUploadAction::DELETE:
    		            $bulkUploadResult->objectId = $this->calculateIdToUpdate($bulkUploadResult);
    		            if (is_null($bulkUploadResult->objectId))
    		            {
    		                $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
    		                $bulkUploadResult->errorDescription = "Category reference ID not found under the provided relativePath";
    		                KBatchBase::unimpersonate();
    		                try {
    		                    $this->addBulkUploadResult($bulkUploadResult);
    		                    KBatchBase::impersonate($this->currentPartnerId);;
    		                }
    		                catch (Exception $e)
    		                {
    		                    KBatchBase::impersonate($this->currentPartnerId);;
    		                }
    		                break;
    		            }
    		            $bulkUploadResultChunk[] = $bulkUploadResult;
                		$requestResults[] = KBatchBase::$kClient->category->delete($bulkUploadResult->objectId);
    		            break;
    		        
    		        default:
    		            $bulkUploadResult->status = BorhanBulkUploadResultStatus::ERROR;
    		            $bulkUploadResult->errorDescription = "Unknown action passed: [".$bulkUploadResult->action ."]";
    		            break;
    		    }
		    }
		    catch (Exception $e)
		    {
		        $requestResults[] = $e;
		    }
		    
		}
		
		KBatchBase::unimpersonate();
		// make all the category actions as the partner
		
		if(count($requestResults))
			$this->updateObjectsResults($requestResults, $bulkUploadResultChunk);

		BorhanLog::info("job[{$this->job->id}] finish modifying categories");
	}
	
	/**
	 * Function to create a new category from bulk upload result.
	 * @param BorhanBulkUploadResultCategory $bulkUploadResult
	 */
	protected function createCategoryFromResultAndJobData (BorhanBulkUploadResultCategory $bulkUploadCategoryResult)
	{
	    $category = new BorhanCategory();
	    $category->name = $bulkUploadCategoryResult->name;
	    //$category->owner = $this->job->data->userId;
	    //calculate parentId of the category
	    if ($bulkUploadCategoryResult->relativePath)
	        $category->parentId = $this->calculateParentId($bulkUploadCategoryResult->relativePath);
	        
	    if ($bulkUploadCategoryResult->tags)
	        $category->tags = $bulkUploadCategoryResult->tags;
	        
	    if ($bulkUploadCategoryResult->description)
	        $category->description = $bulkUploadCategoryResult->description;
	        
	    if ($bulkUploadCategoryResult->referenceId)
	        $category->referenceId = $bulkUploadCategoryResult->referenceId; 
	           
	    if ($bulkUploadCategoryResult->contributionPolicy)
	        $category->contributionPolicy = $bulkUploadCategoryResult->contributionPolicy;

	    if ($bulkUploadCategoryResult->privacy)
	        $category->privacy = $bulkUploadCategoryResult->privacy;
	        
	    if ($bulkUploadCategoryResult->appearInList)
	        $category->appearInList = $bulkUploadCategoryResult->appearInList;
	        
	    if ($bulkUploadCategoryResult->inheritanceType)
	        $category->inheritanceType = $bulkUploadCategoryResult->inheritanceType;
	        
	    if ($bulkUploadCategoryResult->owner)
	        $category->owner = $bulkUploadCategoryResult->owner;

	    if (!is_null($bulkUploadCategoryResult->defaultPermissionLevel))
	        $category->defaultPermissionLevel = $bulkUploadCategoryResult->defaultPermissionLevel;

	    if (!is_null($bulkUploadCategoryResult->userJoinPolicy))
	        $category->userJoinPolicy = $bulkUploadCategoryResult->userJoinPolicy;
	        
	    if (!is_null($bulkUploadCategoryResult->partnerSortValue))
	        $category->partnerSortValue = $bulkUploadCategoryResult->partnerSortValue;

	    if ($bulkUploadCategoryResult->partnerData)
	        $category->partnerData = $bulkUploadCategoryResult->partnerData;
	    
	    if (!is_null($bulkUploadCategoryResult->moderation))
	        $category->moderation = $bulkUploadCategoryResult->moderation;
	        
	    return $category;
	}
	
	protected function calculateParentId ($fullname)
	{
	    $parentCategoryFilter = new BorhanCategoryFilter();
	    $parentCategoryFilter->fullNameEqual = $fullname;
	    $parentCategoryIds = KBatchBase::$kClient->category->listAction($parentCategoryFilter);
	    /* @var $parentCategoryIds BorhanCategoryListResponse*/
	    if (!count($parentCategoryIds->objects))
	    {
	        throw new Exception("Parent category not found for full name [$fullname]");
	    }
	    if (count($parentCategoryIds->objects) > 1)
	    {
	        throw new Exception("Multiple [" . count($parentCategoryIds->objects) . "] parent categories found for full name [$fullname]");
	    }
	    $parentCategory = reset($parentCategoryIds->objects);
	    return $parentCategory->id;
	}
	
	protected function calculateIdToUpdate (BorhanBulkUploadResultCategory $bulkUploadResult)
	{
	    if ($bulkUploadResult->objectId)
	    {
	        return $bulkUploadResult->objectId;
	    }
	    else if ($bulkUploadResult->referenceId)
	    {
	        $categoryFilter = new BorhanCategoryFilter();
	        $categoryFilter->referenceIdEqual = $bulkUploadResult->referenceId;
	        $categoryFilter->fullNameStartsWith = $bulkUploadResult->relativePath;
	        $categoryList = KBatchBase::$kClient->category->listAction($categoryFilter);
	        if (count($categoryList->objects))
	        {
	        	$category = reset($categoryList->objects);
	            return $category->id;
	        }
	    }
	    
	    return null;
	}
	
	/**
	 * 
	 * Gets the columns for V1 csv file
	 */
	protected function getColumns()
	{
		return array(
		    "action",
		    "categoryId",
		    "name",
		    "relativePath",
		    "tags",
		    "description",
		    "referenceId",
		    "contributionPolicy",
		    "privacy",
		    "inheritanceType",
		    "owner",
			"userJoinPolicy",
		    "appearInList",
		    "defaultPermissionLevel",
		    "partnerSortValue",
		    "partnerData",
		    "moderation",
		);
	}
	
	
    protected function updateObjectsResults(array $requestResults, array $bulkUploadResults)
	{
	    KBatchBase::$kClient->startMultiRequest();
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
			
			// update the results with the new object Id
			if ($requestResult->id)
			    $bulkUploadResult->objectId = $requestResult->id;
			$this->addBulkUploadResult($bulkUploadResult);
		}
		
		KBatchBase::$kClient->doMultiRequest();
	}
	
	protected function getUploadResultInstance ()
	{
	    return new BorhanBulkUploadResultCategory();
	}
	
	public function getObjectTypeTitle()
	{
		return self::OBJECT_TYPE_TITLE;
	}

}
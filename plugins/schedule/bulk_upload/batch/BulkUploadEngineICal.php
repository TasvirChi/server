<?php
/**
 * Class which parses the bulk upload iCal and creates the objects defined in it.
 *
 * @package plugins.scheduleBulkUpload
 * @subpackage batch
 */
class BulkUploadEngineICal extends KBulkUploadEngine
{
    const OBJECT_TYPE_TITLE = 'schedule-event';
    const CHUNK_SIZE = 20;
    const MAX_IN_FILTER = 100;

    /**
     * @var int
     */
    protected $itemIndex = 0;
    
	/**
	 * The bulk upload results
	 * @var array
	 */
	protected $bulkUploadResults = array();
    
	/**
	 * The bulk upload items
	 * @var array<kSchedulingICalEvent>
	 */
	protected $items = array();
    
    protected function createUploadResults()
    {
    	$items = $this->items;
    	
		$this->itemIndex = $this->getStartIndex($this->job->id);
		if($this->itemIndex)
		{
			$items = array_slice($items, $this->itemIndex);
		}
		
		$chunks = array_chunk($items, self::CHUNK_SIZE);
		foreach($chunks as $chunk)
		{
			KBatchBase::$kClient->startMultiRequest();
			foreach($chunk as $item)
			{
				/* @var $item kSchedulingICalEvent */
				$bulkUploadResult = $this->createUploadResult($item);
				if($bulkUploadResult)
				{
					$this->bulkUploadResults[] = $bulkUploadResult;
				}
				else
				{
					break;
				}
			}
			KBatchBase::$kClient->doMultiRequest();
		}
    }
    
    protected function createUploadResult(kSchedulingICalEvent $iCal)
    {
    	if($this->handledRecordsThisRun > $this->maxRecordsEachRun)
    	{
    		$this->exceededMaxRecordsEachRun = true;
    		return null;
    	}
    	$this->handledRecordsThisRun++;
    
    	$bulkUploadResult = new BorhanBulkUploadResultScheduleEvent();
    	$bulkUploadResult->bulkUploadJobId = $this->job->id;
    	$bulkUploadResult->lineIndex = $this->itemIndex;
    	$bulkUploadResult->partnerId = $this->job->partnerId;
    	$bulkUploadResult->referenceId = $iCal->getUid();
    	$bulkUploadResult->bulkUploadResultObjectType = BorhanBulkUploadObjectType::SCHEDULE_EVENT;
    	$bulkUploadResult->rowData = $iCal->getRaw();
		$bulkUploadResult->objectStatus = BorhanScheduleEventStatus::ACTIVE;
		$bulkUploadResult->status = BorhanBulkUploadResultStatus::IN_PROGRESS;

    	if($iCal->getMethod() == kSchedulingICal::METHOD_CANCEL)
    	{
    		$bulkUploadResult->action = BorhanBulkUploadAction::CANCEL;
    	}
    
    	$this->itemIndex++;

    	return $bulkUploadResult;
    }

    protected function updateObjectsResults(array $requestResults, array $bulkUploadResults)
    {
    	KBatchBase::$kClient->startMultiRequest();
    
    	// checking the created entries
    	foreach($requestResults as $index => $requestResult)
    	{
    		$bulkUploadResult = $bulkUploadResults[$index];
    			
    		if(KBatchBase::$kClient->isError($requestResult))
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
    
    protected function getExistingEvents()
    {
    	$schedulePlugin = BorhanScheduleClientPlugin::get(KBatchBase::$kClient);

    	$pager = new BorhanFilterPager();
    	$pager->pageSize = self::MAX_IN_FILTER;
    	
		KBatchBase::$kClient->startMultiRequest();
		$referenceIds = array();
		foreach($this->bulkUploadResults as $bulkUploadResult)
		{
			/* @var $bulkUploadResult BorhanBulkUploadResultScheduleEvent */
		    if($bulkUploadResult->action == BorhanBulkUploadAction::CANCEL)
		    	continue;
		    
		    $item = $this->items[$bulkUploadResult->lineIndex];
		    /* @var $item kSchedulingICalEvent */
		    
		    if(!$item->getUid())
		    	continue;
		    
		    $referenceIds[] = $item->getUid();
		    if(count($referenceIds) >= self::MAX_IN_FILTER)
		    {
		    	$filter = new BorhanScheduleEventFilter();
		    	$filter->referenceIdIn = implode(',', $referenceIds);
		    	$schedulePlugin->scheduleEvent->listAction($filter, $pager);
		    }
		}
	    if(count($referenceIds))
	    {
	    	$filter = new BorhanScheduleEventFilter();
	    	$filter->referenceIdIn = implode(',', $referenceIds);
	    	$schedulePlugin->scheduleEvent->listAction($filter, $pager);
	    	$referenceIds = array();
	    }
		$results = KBatchBase::$kClient->doMultiRequest();

		$existingEvents = array();
	    if (is_array($results) || is_object($results))
	    {
		    foreach($results as $result)
		    {
			    KBatchBase::$kClient->throwExceptionIfError($result);
			    /* @var $result BorhanScheduleEventListResponse */
			    foreach($result->objects as $scheduleEvent)
			    {
				    /* @var $scheduleEvent BorhanScheduleEvent */
				    $existingEvents[$scheduleEvent->referenceId] = $scheduleEvent->id;
			    }
		    }
	    }
	    return $existingEvents;
    }
    
    protected function createObjects()
    {
    	$schedulePlugin = BorhanScheduleClientPlugin::get(KBatchBase::$kClient);
		
		$existingEvents = $this->getExistingEvents();

		KBatchBase::$kClient->startMultiRequest();
		
		$bulkUploadResultChunk = array();
		foreach($this->bulkUploadResults as $bulkUploadResult)
		{
		    $item = $this->items[$bulkUploadResult->lineIndex];
		    /* @var $item kSchedulingICalEvent */
		    
			$bulkUploadResultChunk[] = $bulkUploadResult;
			KBatchBase::impersonate($this->currentPartnerId);;
			
			/* @var $bulkUploadResult BorhanBulkUploadResultScheduleEvent */
			if($bulkUploadResult->action == BorhanBulkUploadAction::CANCEL)
			{
				$schedulePlugin->scheduleEvent->cancel($bulkUploadResult->referenceId);
			}
			elseif (isset($existingEvents[$bulkUploadResult->referenceId]))
			{
				$scheduleEventId = $existingEvents[$bulkUploadResult->referenceId];
				$schedulePlugin->scheduleEvent->update($scheduleEventId, $item->toObject());
			}
			else 
			{
				$schedulePlugin->scheduleEvent->add($item->toObject());
			}
			
			KBatchBase::unimpersonate();
		
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
	 * {@inheritDoc}
	 * @see KBulkUploadEngine::handleBulkUpload()
	 */
	public function handleBulkUpload()
	{
		$calendar = kSchedulingICal::parse(file_get_contents($this->data->filePath), $this->data->eventsType);
		$this->items = $calendar->getComponents();
		
		$this->createUploadResults();
		$this->createObjects();
	}

	/**
	 * {@inheritDoc}
	 * @see KBulkUploadEngine::getObjectTypeTitle()
	 */
	public function getObjectTypeTitle()
	{
		return self::OBJECT_TYPE_TITLE;
	}
}

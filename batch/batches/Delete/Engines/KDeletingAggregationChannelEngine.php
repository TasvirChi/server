<?php
/**
 * @package Scheduler
 * @subpackage Delete
 */
class KDeletingAggregationChannelEngine extends  KDeletingEngine
{
	protected $lastCreatedAt;
	
	protected $publicAggregationChannel;
	protected $excludedCategories;
	
	public function configure($partnerId, $jobData)
	{
		/* @var $jobData BorhanDeleteJobData */
		parent::configure($partnerId, $jobData);

		$this->publicAggregationChannel = $jobData->filter->aggregationCategoriesMultiLikeAnd;
		$this->excludedCategories = $this->retrievePublishingCategories ($jobData->filter);
	}
	
	/* (non-PHPdoc)
	 * @see KDeletingEngine::delete()
	 */
	protected function delete(BorhanFilter $filter) {
		return $this->deleteAggregationCategoryEntries ($filter);
		
	}
	
	protected function deleteAggregationCategoryEntries (BorhanCategoryFilter $filter)
	{
		$entryFilter = new BorhanBaseEntryFilter();
		$entryFilter->categoriesIdsNotContains = $this->excludedCategories;
		$entryFilter->categoriesIdsMatchAnd = $this->publicAggregationChannel . "," . $filter->idNotIn;
		
		$entryFilter->orderBy = BorhanBaseEntryOrderBy::CREATED_AT_ASC;
		if ($this->lastCreatedAt)
		{
			$entryFilter->createdAtGreaterThanOrEqual = $this->lastCreatedAt;
		}
		
		$entryFilter->statusIn = implode (',', array (BorhanEntryStatus::ERROR_CONVERTING, BorhanEntryStatus::ERROR_IMPORTING, BorhanEntryStatus::IMPORT, BorhanEntryStatus::NO_CONTENT, BorhanEntryStatus::READY));
		$entriesList = KBatchBase::$kClient->baseEntry->listAction($entryFilter, $this->pager);
		if(!count($entriesList->objects))
			return 0;
			
		$this->lastCreatedAt = $entriesList->objects[count ($entriesList->objects) -1];
		KBatchBase::$kClient->startMultiRequest();
		foreach($entriesList->objects as $entry)
		{
			/* @var $entry BorhanBaseEntry */
			KBatchBase::$kClient->categoryEntry->delete($entry->id, $this->publicAggregationChannel);
		}
		$results = KBatchBase::$kClient->doMultiRequest();
		foreach($results as $index => $result)
			if(is_array($result) && isset($result['code']))
				unset($results[$index]);

		return count($results);
		
	}
	
	protected function retrievePublishingCategories (BorhanCategoryFilter $filter)
	{
		$categoryPager = new BorhanFilterPager();
		$categoryPager->pageIndex = 1;
		$categoryPager->pageSize = 500;
		
		$categoryIdsToReturn = array ();
		
		$categoryResponse = KBatchBase::$kClient->category->listAction($filter, $categoryPager);
		while (count ($categoryResponse->objects))
		{
			foreach ($categoryResponse->objects as $category)
			{
				$categoryIdsToReturn[] = $category->id;
			}
			
			$categoryPager->pageIndex++;
			$categoryResponse = KBatchBase::$kClient->category->listAction($filter, $categoryPager);
		}
		
		return implode (',', $categoryIdsToReturn);
	}

	
}
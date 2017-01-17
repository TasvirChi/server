<?php
/**
 * @package Scheduler
 * @subpackage Index
 */
class KIndexingCategoryEngine extends KIndexingEngine
{
	/* (non-PHPdoc)
	 * @see KIndexingEngine::index()
	 */
	protected function index(BorhanFilter $filter, $shouldUpdate)
	{
		return $this->indexCategories($filter, $shouldUpdate);
	}
	
	/**
	 * @param BorhanCategoryFilter $filter The filter should return the list of categories that need to be reindexed
	 * @param bool $shouldUpdate Indicates that the category columns and attributes values should be recalculated before reindexed
	 * @return int the number of indexed categories
	 */
	protected function indexCategories(BorhanCategoryFilter $filter, $shouldUpdate)
	{
		$filter->orderBy = BorhanCategoryOrderBy::DEPTH_ASC . ',' . BorhanCategoryOrderBy::CREATED_AT_ASC;
		
		$categoriesList = KBatchBase::$kClient->category->listAction($filter, $this->pager);
		if(!count($categoriesList->objects))
			return 0;
			
		KBatchBase::$kClient->startMultiRequest();
		foreach($categoriesList->objects as $category)
		{
			KBatchBase::$kClient->category->index($category->id, $shouldUpdate);
		}
		
		$results = KBatchBase::$kClient->doMultiRequest();
		foreach($results as $index => $result)
			if(!is_int($result))
				unset($results[$index]);
				
		if(!count($results))
			return 0;
				
		$lastIndexId = end($results);
		$this->setLastIndexId($lastIndexId);

		foreach ($categoriesList->objects as $category)
		{
			if($category->id == $lastIndexId)
				$this->setLastIndexDepth($category->depth);
		}

		return count($results);
	}
}

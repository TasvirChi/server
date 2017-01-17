<?php
/**
 * @package Scheduler
 * @subpackage Copy
 */
class KCopyingCategoryEntryEngine extends KCopyingEngine
{
	/* (non-PHPdoc)
	 * @see KCopyingEngine::copy()
	 */
	protected function copy(BorhanFilter $filter, BorhanObjectBase $templateObject) {
		return $this->copyCategoryEntries ($filter, $templateObject);
		
	}

	protected function copyCategoryEntries (BorhanFilter $filter, BorhanObjectBase $templateObject)
	{
		/* @var $filter BorhanCategoryEntryFilter */
		$filter->orderBy = BorhanCategoryEntryOrderBy::CREATED_AT_ASC;
		
		$categoryEntryList = KBatchBase::$kClient->categoryEntry->listAction($filter, $this->pager);
		if(!count($categoryEntryList->objects))
			return 0;
			
		KBatchBase::$kClient->startMultiRequest();
		foreach($categoryEntryList->objects as $categoryEntry)
		{
			$newCategoryEntry = $this->getNewObject($categoryEntry, $templateObject);
			KBatchBase::$kClient->categoryEntry->add($newCategoryEntry);
		}
		
		$results = KBatchBase::$kClient->doMultiRequest();
		foreach($results as $index => $result)
			if(is_array($result) && isset($result['code']))
				unset($results[$index]);
				
		if(!count($results))
			return 0;
			
		$lastCopyId = end($results);
		$this->setLastCopyId($lastCopyId);
		
		return count($results);
	}
	/* (non-PHPdoc)
	 * @see KCopyingEngine::getNewObject()
	 */
	protected function getNewObject(BorhanObjectBase $sourceObject, BorhanObjectBase $templateObject) {
		$class = get_class($sourceObject);
		$newObject = new $class();
		
		/* @var $newObject BorhanCategoryEntry */
		/* @var $sourceObject BorhanCategoryEntry */
		/* @var $templateObject BorhanCategoryEntry */
		
		$newObject->categoryId = $sourceObject->categoryId;
		$newObject->entryId = $sourceObject->entryId;
			
		if(!is_null($templateObject->categoryId))
			$newObject->categoryId = $templateObject->categoryId;
		if(!is_null($templateObject->entryId))
			$newObject->entryId = $templateObject->entryId;
	
		return $newObject;
	}	
}
<?php
/**
 * @package Scheduler
 * @subpackage Delete
 */
class KDeletingCategoryUserEngine extends KDeletingEngine
{
	/* (non-PHPdoc)
	 * @see KDeletingEngine::delete()
	 */
	protected function delete(BorhanFilter $filter)
	{
		return $this->deleteCategoryUsers($filter);
	}
	
	/**
	 * @param BorhanCategoryUserFilter $filter The filter should return the list of category users that need to be deleted
	 * @return int the number of deleted category users
	 */
	protected function deleteCategoryUsers(BorhanCategoryUserFilter $filter)
	{
		$filter->orderBy = BorhanCategoryUserOrderBy::CREATED_AT_ASC;
		
		$categoryUsersList = KBatchBase::$kClient->categoryUser->listAction($filter, $this->pager);
		if(!count($categoryUsersList->objects))
			return 0;
			
		KBatchBase::$kClient->startMultiRequest();
		foreach($categoryUsersList->objects as $categoryUser)
		{
			/* @var $categoryUser BorhanCategoryUser */
			KBatchBase::$kClient->categoryUser->delete($categoryUser->categoryId, $categoryUser->userId);
		}
		$results = KBatchBase::$kClient->doMultiRequest();
		foreach($results as $index => $result)
			if(is_array($result) && isset($result['code']))
				unset($results[$index]);
				
		return count($results);
	}
}

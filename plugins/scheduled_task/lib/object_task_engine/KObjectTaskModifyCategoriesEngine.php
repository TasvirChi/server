<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskModifyCategoriesEngine extends KObjectTaskEntryEngineBase
{
	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		/** @var BorhanModifyCategoriesObjectTask $objectTask */
		$objectTask = $this->getObjectTask();
		if (is_null($objectTask))
			return;

		$entryId = $object->id;
		$addRemoveType = $objectTask->addRemoveType;
		$taskCategoryIds = array();
		if (!is_array($objectTask->categoryIds))
			$objectTask->categoryIds = array();
		foreach($objectTask->categoryIds as $categoryIntValue)
		{
			/** @var BorhanString $categoryIntValue */
			$taskCategoryIds[] = $categoryIntValue->value;
		}

		// remove all categories if nothing was configured in the list
		if (count($taskCategoryIds) == 0 && $addRemoveType == BorhanScheduledTaskAddOrRemoveType::REMOVE)
		{
			try
			{
				$this->impersonate($object->partnerId);
				$this->removeAllCategories($entryId);
				$this->unimpersonate();
			}
			catch(Exception $ex)
			{
				$this->unimpersonate();
				BorhanLog::err($ex);
			}
		}
		else
		{
			foreach($taskCategoryIds as $categoryId)
			{
				try
				{
					$this->impersonate($object->partnerId);
					$this->processCategory($entryId, $categoryId, $addRemoveType);
					$this->unimpersonate();
				}
				catch(Exception $ex)
				{
					$this->unimpersonate();
					BorhanLog::err($ex);
				}
			}
		}
	}

	/**
	 * @param $entryId
	 * @param $categoryId
	 * @param $addRemoveType
	 */
	public function processCategory($entryId, $categoryId, $addRemoveType)
	{
		$client = $this->getClient();
		$categoryEntry = null;
		$filter = new BorhanCategoryEntryFilter();
		$filter->entryIdEqual = $entryId;
		$filter->categoryIdEqual = $categoryId;
		$categoryEntryListResponse = $client->categoryEntry->listAction($filter);
		/** @var BorhanCategoryEntry $categoryEntry */
		if (count($categoryEntryListResponse->objects))
			$categoryEntry = $categoryEntryListResponse->objects[0];

		if (is_null($categoryEntry) && $addRemoveType == BorhanScheduledTaskAddOrRemoveType::ADD)
		{
			$categoryEntry = new BorhanCategoryEntry();
			$categoryEntry->entryId = $entryId;
			$categoryEntry->categoryId = $categoryId;
			$client->categoryEntry->add($categoryEntry);
		}
		elseif (!is_null($categoryEntry) && $addRemoveType == BorhanScheduledTaskAddOrRemoveType::REMOVE)
		{
			$client->categoryEntry->delete($entryId, $categoryId);
		}
	}

	/**
	 * @param $entryId
	 */
	public function removeAllCategories($entryId)
	{
		$client = $this->getClient();
		$filter = new BorhanCategoryEntryFilter();
		$filter->entryIdEqual = $entryId;
		$categoryEntryListResponse = $client->categoryEntry->listAction($filter);
		foreach($categoryEntryListResponse->objects as $categoryEntry)
		{
			/** @var $categoryEntry BorhanCategoryEntry */
			$client->categoryEntry->delete($entryId, $categoryEntry->categoryId);
		}
	}
}
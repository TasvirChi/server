<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskDeleteEntryFlavorsEngine extends KObjectTaskEntryEngineBase
{
	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		/** @var BorhanDeleteEntryFlavorsObjectTask $objectTask */
		$objectTask = $this->getObjectTask();
		$deleteType = $objectTask->deleteType;
		$flavorParamsIds = explode(',', $objectTask->flavorParamsIds);
		$client = $this->getClient();

		$pager = new BorhanFilterPager();
		$pager->pageSize = 500; // use max size, throw exception in case we got more than 500 flavors where pagination is not supported
		$filter = new BorhanFlavorAssetFilter();
		$filter->entryIdEqual = $object->id;
		$this->impersonate($object->partnerId);
		try
		{
			$flavorsResponse = $client->flavorAsset->listAction($filter);
			$this->unimpersonate();
		}
		catch(Exception $ex)
		{
			$this->unimpersonate();
			throw $ex;
		}
		if ($flavorsResponse->totalCount > $pager->pageSize)
			throw new Exception('Too many flavors were found where pagination is not supported');

		$flavors = $flavorsResponse->objects;
		BorhanLog::info('Found '.count($flavors). ' flavors');
		if (!count($flavors))
			return;

		BorhanLog::info('Delete type is '.$deleteType);
		switch($deleteType)
		{
			case BorhanDeleteFlavorsLogicType::DELETE_LIST:
				$this->deleteFlavorByList($flavors, $flavorParamsIds);
				break;
			case BorhanDeleteFlavorsLogicType::KEEP_LIST_DELETE_OTHERS:
				$this->deleteFlavorsKeepingConfiguredList($flavors, $flavorParamsIds);
				break;
			case BorhanDeleteFlavorsLogicType::DELETE_KEEP_SMALLEST:
				$this->deleteAllButKeepSmallest($flavors);
				break;
		}
	}

	/**
	 * @param $id
	 */
	protected function deleteFlavor($id, $partnerId)
	{
		$client = $this->getClient();
		$this->impersonate($partnerId);
		try
		{
			$client->flavorAsset->delete($id);
			BorhanLog::info('Flavor id '.$id.' was deleted');
			$this->unimpersonate();
		}
		catch(Exception $ex)
		{
			$this->unimpersonate();
			BorhanLog::err($ex);
			BorhanLog::err('Failed to delete flavor id '.$id);
		}
	}

	protected function findSmallestFlavor($flavors)
	{
		/** @var BorhanFlavorAsset $smallestFlavor */
		$smallestFlavor = null;
		foreach($flavors as $flavor)
		{
			/** @var BorhanFlavorAsset $flavor */
			if ($flavor->status != BorhanFlavorAssetStatus::READY)
				continue;

			if (!$flavor->size) // flavor must have size
				continue;

			if (is_null($smallestFlavor) || $flavor->size < $smallestFlavor->size)
			{
				$smallestFlavor = $flavor;
			}
		}

		return $smallestFlavor;
	}

	/**
	 * @param $flavors
	 * @param $flavorParamsIds
	 */
	protected function deleteFlavorsKeepingConfiguredList(array $flavors, array $flavorParamsIds)
	{
		// make sure at least one flavor will be left from the configured list
		$atLeastOneFlavorWillBeLeft = false;
		foreach ($flavors as $flavor)
		{
			/** @var $flavor BorhanFlavorAsset */
			if ($flavor->status != BorhanFlavorAssetStatus::READY)
				continue;

			if (in_array($flavor->flavorParamsId, $flavorParamsIds))
			{
				$atLeastOneFlavorWillBeLeft = true;
				break;
			}
		}

		if (!$atLeastOneFlavorWillBeLeft)
		{
			BorhanLog::warning('No flavors will be left after deletion, cannot continue.');
			return;
		}

		foreach ($flavors as $flavor)
		{
			/** @var $flavor BorhanFlavorAsset */
			if (!in_array($flavor->flavorParamsId, $flavorParamsIds))
			{
				$this->deleteFlavor($flavor->id, $flavor->partnerId);
			}
		}
	}

	/**
	 * @param $flavors
	 * @param $flavorParams
	 */
	protected function deleteFlavorByList(array $flavors, array $flavorParams)
	{
		foreach ($flavors as $flavor)
		{
			/** @var $flavor BorhanFlavorAsset */
			if (in_array($flavor->flavorParamsId, $flavorParams))
			{
				$this->deleteFlavor($flavor->id, $flavor->partnerId);
			}
		}
	}

	protected function deleteAllButKeepSmallest(array $flavors)
	{
		$smallestFlavor = $this->findSmallestFlavor($flavors);
		if (is_null($smallestFlavor))
		{
			BorhanLog::warning('Smallest flavor was not found, cannot continue');
			return;
		}
		$this->deleteFlavorsKeepingConfiguredList($flavors, array($smallestFlavor->flavorParamsId));
	}
}
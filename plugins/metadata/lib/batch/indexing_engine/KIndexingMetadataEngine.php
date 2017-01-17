<?php
/**
 * @package plugins.metadata
 * @subpackage Scheduler.Index
 */
class KIndexingMetadataEngine extends KIndexingEngine
{
	/**
	 * @param BorhanFilter $filter
	 * @param bool $shouldUpdate
	 * @return int
	 */
	protected function index(BorhanFilter $filter, $shouldUpdate)
	{
		return $this->indexMetadataObjects($filter, $shouldUpdate);
	}

	/**
	 * @param BorhanMetadataFilter $filter
	 * @param $shouldUpdate
	 * @return int
	 */
	protected function indexMetadataObjects(BorhanMetadataFilter $filter, $shouldUpdate)
	{
		$filter->orderBy = BorhanMetadataOrderBy::CREATED_AT_ASC;
		$metadataPlugin = BorhanMetadataClientPlugin::get(KBatchBase::$kClient);
		$metadataList = $metadataPlugin->metadata->listAction($filter, $this->pager);
		if(!count($metadataList->objects))
			return 0;
			
		KBatchBase::$kClient->startMultiRequest();
		foreach($metadataList->objects as $metadata)
		{
			$metadataPlugin->metadata->index($metadata->id, $shouldUpdate);
		}
		
		$results = KBatchBase::$kClient->doMultiRequest();
		foreach($results as $index => $result)
			if(!is_int($result))
				unset($results[$index]);
				
		if(!count($results))
			return 0;
				
		$lastIndexId = end($results);
		$this->setLastIndexId($lastIndexId);
		
		return count($results);
	}
}

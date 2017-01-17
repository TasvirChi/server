<?php
/**
 * @package Scheduler
 * @subpackage Delete
 */
abstract class KDeletingEngine
{
	/**
	 * @var BorhanClient
	 */
	protected $client;
	
	/**
	 * @var BorhanFilterPager
	 */
	protected $pager;
	
	/**
	 * The partner that owns the objects
	 * @var int
	 */
	private $partnerId;
	
	/**
	 * The batch system partner id
	 * @var int
	 */
	private $batchPartnerId;
	
	/**
	 * @param int $objectType of enum BorhanDeleteObjectType
	 * @return KDeletingEngine
	 */
	public static function getInstance($objectType)
	{
		switch($objectType)
		{
			case BorhanDeleteObjectType::CATEGORY_ENTRY:
				return new KDeletingCategoryEntryEngine();
				
			case BorhanDeleteObjectType::CATEGORY_USER:
				return new KDeletingCategoryUserEngine();

			case BorhanDeleteObjectType::GROUP_USER:
				return new KDeletingGroupUserEngine();

			case BorhanDeleteObjectType::CATEGORY_ENTRY_AGGREGATION:
 				return new KDeletingAggregationChannelEngine();
			
			default:
				return BorhanPluginManager::loadObject('KDeletingEngine', $objectType);
		}
	}
	
	/**
	 * @param int $partnerId
	 * @param BorhanDeleteJobData $jobData
  	 * @param BorhanClient $client
  	 */
	public function configure($partnerId, $jobData)
	{
		$this->partnerId = $partnerId;
		$this->batchPartnerId = KBatchBase::$taskConfig->getPartnerId();

		$this->pager = new BorhanFilterPager();
		$this->pager->pageSize = 100;
		
		if(KBatchBase::$taskConfig->params && KBatchBase::$taskConfig->params->pageSize)
			$this->pager->pageSize = KBatchBase::$taskConfig->params->pageSize;
	}

	
	/**
	 * @param BorhanFilter $filter The filter should return the list of objects that need to be reindexed
	 * @param bool $shouldUpdate Indicates that the object columns and attributes values should be recalculated before reindexed
	 * @return int the number of indexed objects
	 */
	public function run(BorhanFilter $filter)
	{
		KBatchBase::impersonate($this->partnerId);
		$ret = $this->delete($filter);
		KBatchBase::unimpersonate();
		
		return $ret;
	}
	
	/**
	 * @param BorhanFilter $filter The filter should return the list of objects that need to be deleted
	 * @return int the number of deleted objects
	 */
	abstract protected function delete(BorhanFilter $filter);
}

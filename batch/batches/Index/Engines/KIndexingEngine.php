<?php
/**
 * @package Scheduler
 * @subpackage Index
 */
abstract class KIndexingEngine
{
	/**
	 * @var BorhanFilterPager
	 */
	protected $pager;
	
	/**
	 * @var int
	 */
	private $lastIndexId;

	/**
	 * @var int
	 */
	private $lastIndexDepth;
	
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
	 * @param int $objectType of enum BorhanIndexObjectType
	 * @return KIndexingEngine
	 */
	public static function getInstance($objectType)
	{
		switch($objectType)
		{
			case BorhanIndexObjectType::ENTRY:
				return new KIndexingEntryEngine();
				
			case BorhanIndexObjectType::CATEGORY:
				return new KIndexingCategoryEngine();
				
			case BorhanIndexObjectType::LOCK_CATEGORY:
				return new KIndexingCategoryEngine();
				
			case BorhanIndexObjectType::CATEGORY_ENTRY:
				return new KIndexingCategoryEntryEngine();
				
			case BorhanIndexObjectType::CATEGORY_USER:
				return new KIndexingCategoryUserEngine();
				
			case BorhanIndexObjectType::USER:
				return new KIndexingKuserPermissionsEngine();
				
			default:
				return BorhanPluginManager::loadObject('KIndexingEngine', $objectType);
		}
	}
	
	/**
	 * @param int $partnerId
	 */
	public function configure($partnerId)
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
	public function run(BorhanFilter $filter, $shouldUpdate)
	{
		KBatchBase::impersonate($this->partnerId);
		$ret = $this->index($filter, $shouldUpdate);
		KBatchBase::unimpersonate();
		
		return $ret;
	}
	
	/**
	 * @param BorhanFilter $filter The filter should return the list of objects that need to be reindexed
	 * @param bool $shouldUpdate Indicates that the object columns and attributes values should be recalculated before reindexed
	 * @return int the number of indexed objects
	 */
	abstract protected function index(BorhanFilter $filter, $shouldUpdate);
	
	/**
	 * @return int $lastIndexId
	 */
	public function getLastIndexId()
	{
		return $this->lastIndexId;
	}

	/**
	 * @param int $lastIndexId
	 */
	protected function setLastIndexId($lastIndexId)
	{
		$this->lastIndexId = $lastIndexId;
	}

	/**
	 * @return int $lastIndexDepth
	 */
	public function getLastIndexDepth()
	{
		return $this->lastIndexDepth;
	}

	/**
	 * @param int $lastIndexDepth
	 */
	protected function setLastIndexDepth($lastIndexDepth)
	{
		$this->lastIndexDepth = $lastIndexDepth;
	}

	
	
}

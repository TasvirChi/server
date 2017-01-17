<?php
/**
 * @package Scheduler
 * @subpackage Copy
 */
abstract class KCopyingEngine
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
	 * @var int
	 */
	private $lastCopyId;
	
	/**
 	 * @var int
 	 */
 	private $lastCreatedAt;
	
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
	 * @param int $objectType of enum BorhanCopyObjectType
	 * @return KCopyingEngine
	 */
	public static function getInstance($objectType)
	{
		switch($objectType)
		{
			case BorhanCopyObjectType::CATEGORY_USER:
				return new KCopyingCategoryUserEngine();
				
			case BorhanCopyObjectType::CATEGORY_ENTRY:
 				return new KCopyingCategoryEntryEngine();
				
			default:
				return BorhanPluginManager::loadObject('KCopyingEngine', $objectType);
		}
	}
	
	/**
	 * @param int $partnerId
	 * @param BorhanClient $client
	 * @param KSchedularTaskConfig $taskConfig
	 */
	public function configure($partnerId)
	{
		$this->partnerId = $partnerId;
		$this->batchPartnerId = KBatchBase::$taskConfig->getPartnerId();

		$this->pager = new BorhanFilterPager();
		$this->pager->pageSize = 100;
		
		if(KBatchBase::$taskConfig->params->pageSize)
			$this->pager->pageSize = KBatchBase::$taskConfig->params->pageSize;
	}
	
	
	/**
	 * @param BorhanFilter $filter The filter should return the list of objects that need to be copied
	 * @param BorhanObjectBase $templateObject Template object to overwrite attributes on the copied object
	 * @return int the number of copied objects
	 */
	public function run(BorhanFilter $filter, BorhanObjectBase $templateObject)
	{
		KBatchBase::impersonate($this->partnerId);
		$ret = $this->copy($filter, $templateObject);
		KBatchBase::unimpersonate();
		
		return $ret;
	}
	
	/**
	 * @param BorhanFilter $filter The filter should return the list of objects that need to be copied
	 * @param BorhanObjectBase $templateObject Template object to overwrite attributes on the copied object
	 * @return int the number of copied objects
	 */
	abstract protected function copy(BorhanFilter $filter, BorhanObjectBase $templateObject);
	
	/**
	 * Creates a new object instance, based on source object and copied attribute from the template object
	 * @param BorhanObjectBase $sourceObject
	 * @param BorhanObjectBase $templateObject
	 * @return BorhanObjectBase
	 */
	abstract protected function getNewObject(BorhanObjectBase $sourceObject, BorhanObjectBase $templateObject);
	
	/**
	 * @return int $lastCopyId
	 */
	public function getLastCopyId()
	{
		return $this->lastCopyId;
	}

	/**
	 * @param int $lastCopyId
	 */
	protected function setLastCopyId($lastCopyId)
	{
		$this->lastCopyId = $lastCopyId;
	}
}

<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectFilterEngine
 */
abstract class KObjectFilterEngineBase
{
	/**
	 * @var BorhanClient
	 */
	protected $_client;

	/**
	 * @var int
	 */
	private $_pageSize;

	/**
	 * @var int
	 */
	private $_pageIndex;

	public function __construct(BorhanClient $client)
	{
		$this->_client = $client;
	}

	/**
	 * @param BorhanFilter $filter
	 * @return BorhanObjectListResponse
	 */
	abstract function query(BorhanFilter $filter);

	/**
	 * @param int $pageIndex
	 */
	public function setPageIndex($pageIndex)
	{
		$this->_pageIndex = $pageIndex;
	}

	/**
	 * @return int
	 */
	public function getPageIndex()
	{
		return $this->_pageIndex;
	}

	/**
	 * @param int $pageSize
	 */
	public function setPageSize($pageSize)
	{
		$this->_pageSize = $pageSize;
	}

	/**
	 * @return int
	 */
	public function getPageSize()
	{
		return $this->_pageSize;
	}

	/**
	 * @return BorhanFilterPager
	 */
	public function getPager()
	{
		$pager = new BorhanFilterPager();
		$pager->pageIndex = $this->_pageIndex;
		$pager->pageSize = $this->_pageSize;
		return $pager;
	}
}
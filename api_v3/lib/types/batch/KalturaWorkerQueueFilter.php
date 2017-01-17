<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanWorkerQueueFilter extends BorhanObject
{
	/**
	 * @var int
	 */
	public $schedulerId;
	
    
	/**
	 * @var int
	 */
	public $workerId;
	
    
	/**
	 * @var BorhanBatchJobType
	 */
	public $jobType;
	
    
	/**
	 * @var BorhanBatchJobFilter
	 */
	public $filter;
	
    
}


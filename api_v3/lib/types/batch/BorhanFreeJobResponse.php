<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFreeJobResponse extends BorhanObject
{
	/**
	 * @var BorhanBatchJob
	 * @readonly 
	 */
	public $job;

	/**
	 * @var BorhanBatchJobType
	 * @readonly 
	 */
    public $jobType;
    
	/**
	 * @var int
	 * @readonly 
	 */
    public $queueSize;
}

?>
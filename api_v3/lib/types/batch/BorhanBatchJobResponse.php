<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBatchJobResponse extends BorhanObject 
{
	/**
	 * The main batch job
	 * 
	 * @var BorhanBatchJob
	 */
	public $batchJob;
	
	
	/**
	 * All batch jobs that reference the main job as root
	 * 
	 * @var BorhanBatchJobArray
	 */
	public $childBatchJobs;
}
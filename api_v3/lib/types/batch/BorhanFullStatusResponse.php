<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFullStatusResponse extends BorhanObject 
{
	/**
	 * The status of all queues on the server
	 * 
	 * @var BorhanBatchQueuesStatusArray
	 */
	public $queuesStatus;
	
	
	/**
	 * Array of all schedulers
	 * 
	 * @var BorhanSchedulerArray
	 */
	public $schedulers;
}
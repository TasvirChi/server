<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanSchedulerStatusResponse extends BorhanObject 
{
	/**
	 * The status of all queues on the server
	 * 
	 * @var BorhanBatchQueuesStatusArray
	 */
	public $queuesStatus;
	
	
	/**
	 * The commands that sent from the control panel
	 * 
	 * @var BorhanControlPanelCommandArray
	 */
	public $controlPanelCommands;
	
	
	/**
	 * The configuration that sent from the control panel
	 * 
	 * @var BorhanSchedulerConfigArray
	 */
	public $schedulerConfigs;
}
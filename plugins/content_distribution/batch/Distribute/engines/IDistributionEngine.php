<?php
/**
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
interface IDistributionEngine
{
	/**
	 * @param KSchedularTaskConfig $taskConfig
	 */
	public function configure();
	
	/**
	 * @param BorhanClient $borhanClient
	 */
	public function setClient();
}
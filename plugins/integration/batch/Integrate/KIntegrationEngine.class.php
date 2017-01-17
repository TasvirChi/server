<?php
/**
 * @package plugins.integration
 * @subpackage Scheduler
 */
interface KIntegrationEngine
{	
	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanIntegrationJobData $data
	 */
	public function dispatch(BorhanBatchJob $job, BorhanIntegrationJobData &$data);
}

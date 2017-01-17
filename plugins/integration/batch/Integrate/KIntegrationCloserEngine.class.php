<?php
/**
 * @package plugins.integration
 * @subpackage Scheduler
 */
interface KIntegrationCloserEngine extends KIntegrationEngine
{	
	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanIntegrationJobData $data
	 */
	public function close(BorhanBatchJob $job, BorhanIntegrationJobData &$data);
}

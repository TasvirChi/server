<?php
/**
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
interface IDistributionEngineCloseDelete extends IDistributionEngine
{
	/**
	 * check for deletion closure in case the deletion is asynchronous.
	 * @param BorhanDistributionDeleteJobData $data
	 * @return bool true if finished, false if will be finished asynchronously
	 */
	public function closeDelete(BorhanDistributionDeleteJobData $data);
}
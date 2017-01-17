<?php
/**
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
interface IDistributionEngineDelete extends IDistributionEngine
{
	/**
	 * removes media.
	 * @param BorhanDistributionDeleteJobData $data
	 * @return bool true if finished, false if will be finished asynchronously
	 */
	public function delete(BorhanDistributionDeleteJobData $data);
}
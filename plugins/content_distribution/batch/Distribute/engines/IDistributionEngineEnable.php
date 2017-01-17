<?php
/**
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
interface IDistributionEngineEnable extends IDistributionEngineUpdate
{
	/**
	 * enables the package.
	 * @param BorhanDistributionEnableJobData $data
	 * @return bool true if finished, false if will be finished asynchronously
	 */
	public function enable(BorhanDistributionEnableJobData $data);
}
<?php
/**
 * Distributes borhan entries to remote destination  
 *
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
class KAsyncDistributeFetchReportCloser extends KAsyncDistributeCloser
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::DISTRIBUTION_FETCH_REPORT;
	}
	
	/* (non-PHPdoc)
	 * @see KAsyncDistribute::getDistributionEngine()
	 */
	protected function getDistributionEngine($providerType, BorhanDistributionJobData $data)
	{
		return DistributionEngine::getEngine('IDistributionEngineCloseReport', $providerType, $data);
	}
	
	/* (non-PHPdoc)
	 * @see KAsyncDistribute::execute()
	 */
	protected function execute(BorhanDistributionJobData $data)
	{
		return $this->engine->closeReport($data);
	}
}

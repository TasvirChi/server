<?php
/**
 * Distributes borhan entries to remote destination  
 *
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
class KAsyncDistributeSubmit extends KAsyncDistribute
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::DISTRIBUTION_SUBMIT;
	}
	
	/* (non-PHPdoc)
	 * @see KAsyncDistribute::getDistributionEngine()
	 */
	protected function getDistributionEngine($providerType, BorhanDistributionJobData $data)
	{
		return DistributionEngine::getEngine('IDistributionEngineSubmit', $providerType, $data);
	}
	
	/* (non-PHPdoc)
	 * @see KAsyncDistribute::execute()
	 */
	protected function execute(BorhanDistributionJobData $data)
	{
		return $this->engine->submit($data);
	}
}

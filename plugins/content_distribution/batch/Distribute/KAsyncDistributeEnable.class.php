<?php
/**
 * Distributes borhan entries to remote destination  
 *
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
class KAsyncDistributeEnable extends KAsyncDistribute
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::DISTRIBUTION_ENABLE;
	}
	
	/* (non-PHPdoc)
	 * @see KAsyncDistribute::getDistributionEngine()
	 */
	protected function getDistributionEngine($providerType, BorhanDistributionJobData $data)
	{
		return DistributionEngine::getEngine('IDistributionEngineEnable', $providerType, $data);
	}
	
	/* (non-PHPdoc)
	 * @see KAsyncDistribute::execute()
	 */
	protected function execute(BorhanDistributionJobData $data)
	{
		return $this->engine->enable($data);
	}
}

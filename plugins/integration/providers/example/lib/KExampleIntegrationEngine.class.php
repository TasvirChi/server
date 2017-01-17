<?php
/**
 * @package plugins.exampleIntegration
 * @subpackage Scheduler
 */
class KExampleIntegrationEngine implements KIntegrationCloserEngine
{	
	/* (non-PHPdoc)
	 * @see KIntegrationCloserEngine::dispatch()
	 */
	public function dispatch(BorhanBatchJob $job, BorhanIntegrationJobData &$data)
	{
		return $this->doDispatch($job, $data, $data->providerData);
	}

	/* (non-PHPdoc)
	 * @see KIntegrationCloserEngine::close()
	 */
	public function close(BorhanBatchJob $job, BorhanIntegrationJobData &$data)
	{
		return $this->doClose($job, $data, $data->providerData);
	}
	
	protected function doDispatch(BorhanBatchJob $job, BorhanIntegrationJobData &$data, BorhanExampleIntegrationJobProviderData $providerData)
	{
		BorhanLog::debug("Example URL [$providerData->exampleUrl]");
		
		// To finish, return true
		// To wait for closer, return false
		// To fail, throw exception
		
		return false;
	}
	
	protected function doClose(BorhanBatchJob $job, BorhanIntegrationJobData &$data, BorhanExampleIntegrationJobProviderData $providerData)
	{
		BorhanLog::debug("Example URL [$providerData->exampleUrl]");
		
		// To finish, return true
		// To keep open for future closer, return false
		// To fail, throw exception
		
		return true;
	}
}

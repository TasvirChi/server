<?php
/**
 * Will scan for viruses on specified file  
 *
 * @package plugins.virusScan
 * @subpackage Scheduler
 */
class KAsyncVirusScan extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::VIRUS_SCAN;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->scan($job, $job->data);
	}
	
	protected function scan(BorhanBatchJob $job, BorhanVirusScanJobData $data)
	{
		try
		{
			$engine = VirusScanEngine::getEngine($job->jobSubType);
			if (!$engine)
			{
				BorhanLog::err('Cannot create VirusScanEngine of type ['.$job->jobSubType.']');
				$this->closeJob($job, BorhanBatchJobErrorTypes::APP, null, 'Error: Cannot create VirusScanEngine of type ['.$job->jobSubType.']', BorhanBatchJobStatus::FAILED);
				return $job;
			}
						
			// configure engine
			if (!$engine->config(self::$taskConfig->params))
			{
				BorhanLog::err('Cannot configure VirusScanEngine of type ['.$job->jobSubType.']');
				$this->closeJob($job, BorhanBatchJobErrorTypes::APP, null, 'Error: Cannot configure VirusScanEngine of type ['.$job->jobSubType.']', BorhanBatchJobStatus::FAILED);
				return $job;
			}
			
			$cleanIfInfected = $data->virusFoundAction == BorhanVirusFoundAction::CLEAN_NONE || $data->virusFoundAction == BorhanVirusFoundAction::CLEAN_DELETE;
			$errorDescription = null;
			$output = null;
			
			// execute scan
			$data->scanResult = $engine->execute($data->srcFilePath, $cleanIfInfected, $output, $errorDescription);
			
			if (!$output) {
				BorhanLog::notice('Virus scan engine ['.get_class($engine).'] did not return any log for file ['.$data->srcFilePath.']');
				$output = 'Virus scan engine ['.get_class($engine).'] did not return any log';
			}
		
			try
			{
				self::$kClient->batch->logConversion($data->flavorAssetId, $output);
			}
			catch(Exception $e)
			{
				BorhanLog::err("Log conversion: " . $e->getMessage());
			}

			// check scan results
			switch ($data->scanResult)
			{
				case BorhanVirusScanJobResult::SCAN_ERROR:
					$this->closeJob($job, BorhanBatchJobErrorTypes::APP, null, "Error: " . $errorDescription, BorhanBatchJobStatus::RETRY, $data);
					break;
				
				case BorhanVirusScanJobResult::FILE_IS_CLEAN:
					$this->closeJob($job, null, null, "Scan finished - file was found to be clean", BorhanBatchJobStatus::FINISHED, $data);
					break;
				
				case BorhanVirusScanJobResult::FILE_WAS_CLEANED:
					$this->closeJob($job, null, null, "Scan finished - file was infected but scan has managed to clean it", BorhanBatchJobStatus::FINISHED, $data);
					break;
					
				case BorhanVirusScanJobResult::FILE_INFECTED:
				
					$this->closeJob($job, null, null, "File was found INFECTED and wasn't cleaned!", BorhanBatchJobStatus::FINISHED, $data);
					break;
					
				default:
					$data->scanResult = BorhanVirusScanJobResult::SCAN_ERROR;
					$this->closeJob($job, BorhanBatchJobErrorTypes::APP, null, "Error: Emtpy scan result returned", BorhanBatchJobStatus::RETRY, $data);
					break;
			}
			
		}
		catch(Exception $ex)
		{
			$data->scanResult = BorhanVirusScanJobResult::SCAN_ERROR;
			$this->closeJob($job, BorhanBatchJobErrorTypes::RUNTIME, $ex->getCode(), "Error: " . $ex->getMessage(), BorhanBatchJobStatus::FAILED, $data);
		}
		return $job;
	}
}

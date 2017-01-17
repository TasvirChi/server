<?php
/**
 * @package Scheduler
 * @subpackage LiveReportExport
 */
class KAsyncLiveReportExport  extends KJobHandlerWorker
{

	public static function getType()
	{
		return BorhanBatchJobType::LIVE_REPORT_EXPORT;
	}

	protected function exec(BorhanBatchJob $job)
	{
		$this->updateJob($job, 'Creating CSV Export', BorhanBatchJobStatus::QUEUED);
		$job = $this->createCsv($job, $job->data);
		return $job;
	}

	protected function createCsv(BorhanBatchJob $job, BorhanLiveReportExportJobData $data) {
		$partnerId =  $job->partnerId;
		$type = $job->jobSubType;
		
		// Create local path for report generation
		$data->outputPath = self::$taskConfig->params->localTempPath . DIRECTORY_SEPARATOR . $partnerId;
		KBatchBase::createDir($data->outputPath);
		
		// Generate report
		KBatchBase::impersonate($job->partnerId);
		$exporter = LiveReportFactory::getExporter($type, $data);
		$reportFile = $exporter->run();
		$this->setFilePermissions($reportFile);
		KBatchBase::unimpersonate();
		
		// Copy the report to shared location.
		$this->moveFile($job, $data, $partnerId);
		
		return $job;
	}
	
	protected function moveFile(BorhanBatchJob $job, BorhanLiveReportExportJobData $data, $partnerId) {
		$fileName =  basename($data->outputPath);
		$sharedLocation = self::$taskConfig->params->sharedPath . DIRECTORY_SEPARATOR . $partnerId . "_" . $fileName;
		
		$fileSize = kFile::fileSize($data->outputPath);
		rename($data->outputPath, $sharedLocation);
		$data->outputPath = $sharedLocation;
		
		$this->setFilePermissions($sharedLocation);
		if(!$this->checkFileExists($sharedLocation, $fileSize))
		{
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::NFS_FILE_DOESNT_EXIST, 'Failed to move report file', BorhanBatchJobStatus::RETRY);
		}
	
		return $this->closeJob($job, null, null, 'CSV created successfully', BorhanBatchJobStatus::FINISHED, $data);
	}
	
}

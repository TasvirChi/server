<?php
/**
 * Interface which allows plugin to add its own configuration to a batch job.
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanBatchJobDataContributor extends IBorhanBase
{
	/**
	 * Contribute to convert job data 
	 * @param kConvertJobData $jobData
	 * @returns kConvertJobData
	 */ 
	public static function contributeToConvertJobData ($jobType, $jobSubType, kConvertJobData $jobData);
}
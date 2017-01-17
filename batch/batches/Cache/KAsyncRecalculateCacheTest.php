<?php
/**
 * @package Scheduler
 * @subpackage Debug
 */
chdir(dirname( __FILE__ ) . "/../../");
require_once(__DIR__ . "/../../bootstrap.php");

/**
 * @package Scheduler
 * @subpackage Debug
 */
class KAsyncRecalculateCacheTest extends PHPUnit_Framework_TestCase
{
	const JOB_NAME = 'KAsyncRecalculateCache';
	
	public function testMediaEntryFilter()
	{
		$filter = new BorhanMediaEntryFilter();
		// TODO define the filter
		
		$this->doTestCategoryUser($filter, BorhanBatchJobStatus::FINISHED);
	}

	public function doTestCategoryUser(BorhanBaseEntryFilter $filter, $expectedStatus)
	{
		$this->doTest(BorhanCopyObjectType::CATEGORY_USER, $filter, $expectedStatus);
	}
	
	public function doTest($objectType, BorhanFilter $filter, $expectedStatus)
	{
		$iniFile = "batch_config.ini";
		$schedulerConfig = new KSchedulerConfig($iniFile);
	
		$taskConfigs = $schedulerConfig->getTaskConfigList();
		$config = null;
		foreach($taskConfigs as $taskConfig)
		{
			if($taskConfig->name == self::JOB_NAME)
				$config = $taskConfig;
		}
		$this->assertNotNull($config);
		
		$jobs = $this->prepareJobs($objectType, $filter);
		
		$config->setTaskIndex(1);
		$instance = new $config->type($config);
		$instance->setUnitTest(true);
		$jobs = $instance->run($jobs); 
		$instance->done();
		
		foreach($jobs as $job)
			$this->assertEquals($expectedStatus, $job->status);
	}
	
	private function prepareJobs($objectType, BorhanFilter $filter)
	{
		$data = new BorhanCopyJobData();
		$data->filter = $filter;
		
		$job = new BorhanBatchJob();
		$job->id = 1;
		$job->jobSubType = $objectType;
		$job->status = BorhanBatchJobStatus::PENDING;
		$job->data = $data;
		
		return array($job);
	}
}

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
class KAsyncImportTest extends PHPUnit_Framework_TestCase
{
	const JOB_NAME = 'KAsyncImport';
	
	public function setUp() 
	{
		parent::setUp();
	}
	
	public function tearDown() 
	{
		parent::tearDown();
	}
	
	public function testGoodUrl()
	{
		$this->doTest('http://kaldev.borhan.com/content/zbale/9spkxiz8m4_100007.mp4', BorhanBatchJobStatus::FINISHED);
	}
	
//	public function testSpecialCharsUrl()
//	{
//		$this->doTest('http://kaldev.borhan.com/content/zbale/trailer_480 ()p.mov', BorhanBatchJobStatus::FINISHED);
//	}
//	
//	public function testSpacedUrl()
//	{
//		$this->doTest(' http://kaldev.borhan.com/content/zbale/9spkxiz8m4_100007.mp4', BorhanBatchJobStatus::FINISHED);
//	}
//	
//	public function testMissingFileUrl()
//	{
//		$this->doTest('http://localhost/api_v3/sample/xxx.avi', BorhanBatchJobStatus::FAILED);
//	}
//	
//	public function testInvalidServerUrl()
//	{
//		$this->doTest('http://xxx', BorhanBatchJobStatus::FAILED);
//	}
//	
//	public function testInvalidUrl()
//	{
//		$this->doTest('xxx', BorhanBatchJobStatus::FAILED);
//	}
//	
//	public function testEmptyUrl()
//	{
//		$this->doTest('', BorhanBatchJobStatus::FAILED);
//	}
	
	public function doTest($value, $expectedStatus)
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
		
		$jobs = $this->prepareJobs($value);
		
		$config->setTaskIndex(1);
		$instance = new $config->type($config);
		$instance->setUnitTest(true);
		$jobs = $instance->run($jobs); 
		$instance->done();
		
		foreach($jobs as $job)
			$this->assertEquals($expectedStatus, $job->status);
	}
	
	private function prepareJobs($value)
	{
		$data = new BorhanImportJobData();
		$data->srcFileUrl = $value;
		
		$job = new BorhanBatchJob();
		$job->id = 1;
		$job->status = BorhanBatchJobStatus::PENDING;
		$job->data = $data;
		
		return array($job);
	}
}

?>
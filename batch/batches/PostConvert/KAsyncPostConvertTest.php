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
class KAsyncPostConvertTest extends PHPUnit_Framework_TestCase 
{
	const JOB_NAME = 'KAsyncPostConvert';
	
	public function setUp() 
	{
		parent::setUp();
	}
	
	public function tearDown() 
	{
		parent::tearDown();
	}
	
	public function testGoodFile()
	{
		$this->doTest(realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi'), true, BorhanBatchJobStatus::FINISHED);
	}
	
	public function testSpacedFile()
	{
		$path = realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi');
		$this->doTest(" $path", true, BorhanBatchJobStatus::FINISHED);
	}
	
	public function testMissingFile()
	{
		$this->doTest('aaa', true, BorhanBatchJobStatus::FAILED);
	}
	
	public function doTest($filePath, $createThumb, $expectedStatus)
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
		
		$jobs = $this->prepareJobs($filePath, $createThumb);
		
		$config->setTaskIndex(1);
		$instance = new $config->type($config);
		$instance->setUnitTest(true);
		$jobs = $instance->run($jobs); 
		$instance->done();
		
		foreach($jobs as $job)
			$this->assertEquals($expectedStatus, $job->status);
	}
	
	private function prepareJobs($filePath, $createThumb)
	{
		$data = new BorhanPostConvertJobData();
		$srcFileSyncDescriptor = new BorhanSourceFileSyncDescriptor();
		$srcFileSyncDescriptor->fileSyncLocalPath = $filePath;
		$data->srcFileSyncs = new BorhanSourceFileSyncDescriptorArray();
		$data->srcFileSyncs[] = $srcFileSyncDescriptor;		
		$data->createThumb = $createThumb;
		
		$job = new BorhanBatchJob();
		$job->id = 1;
		$job->status = BorhanBatchJobStatus::PENDING;
		$job->data = $data;
		
		return array($job);
	}
}

?>
<?php
/**
 * @package Scheduler
 * @subpackage Debug
 */
chdir(dirname( __FILE__ ) . "/../../");
require_once(dirname( __FILE__ ) . "/../../bootstrap.php");

/**
 * @package Scheduler
 * @subpackage Debug
 */
class KAsyncConvertTest extends PHPUnit_Framework_TestCase 
{
	const JOB_NAME = 'KAsyncConvert';
	
	public function setUp() 
	{
		parent::setUp();
	}
	
	public function tearDown() 
	{
		parent::tearDown();
	}
	
	public function testFFMPEG()
	{
		$this->doTestHD(BorhanConversionEngineType::FFMPEG);
		$this->doTestHQ(BorhanConversionEngineType::FFMPEG);
		$this->doTestNormalBig(BorhanConversionEngineType::FFMPEG);
		$this->doTestNormalSmall(BorhanConversionEngineType::FFMPEG);
		$this->doTestLowSmall(BorhanConversionEngineType::FFMPEG);
	}
	
	public function testMENCODER()
	{
		$this->doTestHD(BorhanConversionEngineType::MENCODER);
		$this->doTestHQ(BorhanConversionEngineType::MENCODER);
		$this->doTestNormalBig(BorhanConversionEngineType::MENCODER);
		$this->doTestNormalSmall(BorhanConversionEngineType::MENCODER);
		$this->doTestLowSmall(BorhanConversionEngineType::MENCODER);
	}
	
	public function testON2()
	{
		$this->doTestHD(BorhanConversionEngineType::ON2);
		$this->doTestHQ(BorhanConversionEngineType::ON2);
		$this->doTestNormalBig(BorhanConversionEngineType::ON2);
		$this->doTestNormalSmall(BorhanConversionEngineType::ON2);
		$this->doTestLowSmall(BorhanConversionEngineType::ON2);
	}
	
	private function doTestHD($engineType)
	{
		$filePath = realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi');
		$flavorParams = $this->prepareFlavorParams('mp4', 'h264', '1920', '1080', '4000');
		$this->doTest($engineType, $filePath, $flavorParams, BorhanBatchJobStatus::FINISHED);
	}
	
	private function doTestHQ($engineType)
	{
		$filePath = realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi');
		$flavorParams = $this->prepareFlavorParams('mp4', 'h264', '1280', '720', '2500');
		$this->doTest($engineType, $filePath, $flavorParams, BorhanBatchJobStatus::FINISHED);
	}
	
	private function doTestNormalBig($engineType)
	{
		$filePath = realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi');
		$flavorParams = $this->prepareFlavorParams('mp4', 'h264', '1280', '720', '1350');
		$this->doTest($engineType, $filePath, $flavorParams, BorhanBatchJobStatus::FINISHED);
	}
	
	private function doTestNormalSmall($engineType)
	{
		$filePath = realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi');
		$flavorParams = $this->prepareFlavorParams('mp4', 'h264', '512', '288', '750');
		$this->doTest($engineType, $filePath, $flavorParams, BorhanBatchJobStatus::FINISHED);
	}
	
	private function doTestLowSmall($engineType)
	{
		$filePath = realpath(dirname( __FILE__ ) . '/../../../tests/files/example.avi');
		$flavorParams = $this->prepareFlavorParams('mp4', 'h264', '512', '288', '00');
		$this->doTest($engineType, $filePath, $flavorParams, BorhanBatchJobStatus::FINISHED);
	}
	
	private function prepareFlavorParams($fmt, $codec, $w, $h, $br)
	{
		$flavorParams = new BorhanFlavorParams();
		
		$flavorParams->format = $fmt;
		
		$flavorParams->videoCodec = $codec;
		$flavorParams->videoBitrate = $br;
		$flavorParams->width = $w;
		$flavorParams->height = $h;
		
		$flavorParams->frameRate = 30;

		$flavorParams->audioCodec = "mp3";
		$flavorParams->audioChannels = 2;
		$flavorParams->audioSampleRate = 44100;
		$flavorParams->audioBitrate = 96;
		//$flavorParams->audioResolution = 16;

		return $flavorParams;
	}
	
	private function doTest($engineType, $filePath, $flavorParams, $expectedStatus)
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
		
		$jobs = $this->prepareJobs($engineType, $filePath, $flavorParams);
		
		$config->setTaskIndex(1);
		$instance = new $config->type($config);
		$instance->setUnitTest(true);
		$jobs = $instance->run($jobs); 
		$instance->done();
		
		foreach($jobs as $job)
			$this->assertEquals($expectedStatus, $job->status);
	}
	
	private function prepareJobs($engineType, $filePath, $flavorParams)
	{
		$data = new BorhanConvertJobData();
		$srcFileSyncDescriptor = new BorhanSourceFileSyncDescriptor();
		$srcFileSyncDescriptor->fileSyncLocalPath = $filePath;
		$data->srcFileSyncs = new BorhanSourceFileSyncDescriptorArray();
		$data->srcFileSyncs[] = $srcFileSyncDescriptor;
		$data->flavorParamsOutput = $flavorParams;
		
		$job = new BorhanBatchJob();
		$job->id = 1;
		$job->jobSubType = $engineType;
		$job->status = BorhanBatchJobStatus::PENDING;
		$job->data = $data;
		
		return array($job);
	}
}

?>
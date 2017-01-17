<?php
$config = array();
$client = null;
/* @var $client BorhanClient */
require_once __DIR__  . '/common.php';

$options = getopt('', array(
	'service-url:',
	'debug',
	'job-type:',
	'job-sub-type:',
));

if(!isset($options['job-type']))
{
	echo "Arguments job-type is required";
	exit(-1);
}
$jobType = $options['job-type'];

 if (!defined("BorhanBatchJobType::$jobType"))
{
	echo "job-type $jobType is not defined";
	exit(-1);
}

$monitorResult = new BorhanMonitorResult();
$apiCall = null;

try
{
	$apiCall = 'session.start';
	$start = microtime(true);
	$ks = $client->session->start($config['batch-partner']['adminSecret'], "",  BorhanSessionType::ADMIN, $config['batch-partner']['id']);
	$client->setKs($ks);
		
	
	$apiCall = 'batch.getQueueSize';
	$workerQueueFilter = new BorhanWorkerQueueFilter();
	$workerQueueFilter->jobType = constant("BorhanBatchJobType::$jobType");
	$batchJobFilter = new BorhanBatchJobFilter();
	if (isset($options['job-sub-type'])) {
		$batchJobFilter->jobSubTypeEqual = $options['job-sub-type'];
	}
	$workerQueueFilter->filter = $batchJobFilter;
	
	$start = microtime(true);
	$queueSize = $client->batch->getQueueSize($workerQueueFilter);
 	$requestEnd =  microtime(true);
	$monitorResult->executionTime = $requestEnd - $start;
	$monitorResult->value =  $queueSize;
	$monitorResult->description = "Scheduler Queue for $jobType is: $monitorResult->value";
}
catch(BorhanException $e)
{
	$end = microtime(true);
	$monitorResult->executionTime = $end - $start;
	
	$error = new BorhanMonitorError();
	$error->code = $e->getCode();
	$error->description = $e->getMessage();
	$error->level = BorhanMonitorError::ERR;
	
	$monitorResult->errors[] = $error;
	$monitorResult->description = "Exception: " . get_class($e) . ", API: $apiCall, Code: " . $e->getCode() . ", Message: " . $e->getMessage();
}
catch(BorhanClientException $ce)
{
	$end = microtime(true);
	$monitorResult->executionTime = $end - $start;
	
	$error = new BorhanMonitorError();
	$error->code = $ce->getCode();
	$error->description = $ce->getMessage();
	$error->level = BorhanMonitorError::CRIT;
	
	$monitorResult->errors[] = $error;
	$monitorResult->description = "Exception: " . get_class($ce) . ", API: $apiCall, Code: " . $ce->getCode() . ", Message: " . $ce->getMessage();
}

echo "$monitorResult";
exit(0);

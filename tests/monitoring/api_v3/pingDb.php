<?php
$client = null;
/* @var $client BorhanClient */
require_once __DIR__  . '/common.php';

$start = microtime(true);
$monitorResult = new BorhanMonitorResult();
try
{
	$res = $client->system->pingDatabase();
	$end = microtime(true);
	$monitorResult->executionTime = $end - $start;
	
	if($res)
	{
		$monitorResult->value = $monitorResult->executionTime;
		$monitorResult->description = "Database ping time: $monitorResult->value seconds";
	}
	else
	{
		$monitorResult->value = -1;
		$monitorResult->description = 'Database ping failed';
	}
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
	$monitorResult->description = "Exception: " . get_class($e) . ", Code: " . $e->getCode() . ", Message: " . $e->getMessage();
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
	$monitorResult->description = "Exception: " . get_class($ce) . ", Code: " . $ce->getCode() . ", Message: " . $ce->getMessage();
}

echo "$monitorResult";
exit(0);


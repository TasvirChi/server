<?php
$config = array();
$client = null;
$serviceUrl = null;
/* @var $client BorhanClient */
require_once __DIR__  . '/common.php';

$options = getopt('', array(
	'service-url:',
	'debug',
	'timeout:',
	'media-url:',
));

if(!isset($options['timeout']))
{
	echo "Argument timeout is required";
	exit(-1);
}
$timeout = $options['timeout'];

$mediaUrl = $serviceUrl . '/content/templates/entry/data/borhan_logo_animated_blue.flv';
if(isset($options['media-url']))
	$mediaUrl = $options['media-url'];

$start = microtime(true);
$monitorResult = new BorhanMonitorResult();
$apiCall = null;
try
{
	$apiCall = 'session.start';
	$ks = $client->session->start($config['monitor-partner']['secret'], 'monitor-user', BorhanSessionType::USER, $config['monitor-partner']['id']);
	$client->setKs($ks);
		
	 // Creates a new entry
	$entry = new BorhanMediaEntry();
	$entry->name = 'monitor-test';
	$entry->description = 'monitor-test';
	$entry->mediaType = BorhanMediaType::VIDEO;
	
	$resource = new BorhanUrlResource();
	$resource->url = $mediaUrl;
	
	$apiCall = 'multirequest';
	$client->startMultiRequest();
	$requestEntry = $client->media->add($entry);
	/* @var $requestEntry BorhanMediaEntry */
	$client->media->addContent($requestEntry->id, $resource);
	$client->media->get($requestEntry->id);
	
	$results = $client->doMultiRequest();
	foreach($results as $index => $result)
	{
		if ($client->isError($result))
			throw new BorhanException($result["message"], $result["code"]);
	}
		
	// Waits for the entry to start conversion
	$createdEntry = end($results);
	$timeoutTime = time() + $timeout;
	/* @var $createdEntry BorhanMediaEntry */
	while ($createdEntry)
	{
		if(time() > $timeoutTime)
			throw new Exception("timed out, entry id: $createdEntry->id");
			
		if($createdEntry->status == BorhanEntryStatus::IMPORT)
		{
			sleep(1);
			$apiCall = 'media.get';
			$createdEntry = $client->media->get($createdEntry->id);
			continue;
		}
		
		$monitorResult->executionTime = microtime(true) - $start;
		$monitorResult->value = $monitorResult->executionTime;
		
		if($createdEntry->status == BorhanEntryStatus::READY || $createdEntry->status == BorhanEntryStatus::PRECONVERT)
		{
			$monitorResult->description = "import time: $monitorResult->executionTime seconds";
		}
		elseif($createdEntry->status == BorhanEntryStatus::ERROR_IMPORTING)
		{
			$error = new BorhanMonitorError();
			$error->description = "import failed, entry id: $createdEntry->id";
			$error->level = BorhanMonitorError::CRIT;
			
			$monitorResult->errors[] = $error;
			$monitorResult->description = "import failed, entry id: $createdEntry->id";
		}
		else
		{
			$error = new BorhanMonitorError();
			$error->description = "unexpected entry status: $createdEntry->status, entry id: $createdEntry->id";
			$error->level = BorhanMonitorError::CRIT;
			
			$monitorResult->errors[] = $error;
			$monitorResult->description = "unexpected entry status: $createdEntry->status, entry id: $createdEntry->id";
		}
		
		break;
	}

	try
	{
		$apiCall = 'media.delete';
		$createdEntry = $client->media->delete($createdEntry->id);
	}
	catch(Exception $ex)
	{
		$error = new BorhanMonitorError();
		$error->code = $ex->getCode();
		$error->description = $ex->getMessage();
		$error->level = BorhanMonitorError::WARN;
		
		$monitorResult->errors[] = $error;
	}
}
catch(BorhanException $e)
{
	$monitorResult->executionTime = microtime(true) - $start;
	
	$error = new BorhanMonitorError();
	$error->code = $e->getCode();
	$error->description = $e->getMessage();
	$error->level = BorhanMonitorError::ERR;
	
	$monitorResult->errors[] = $error;
	$monitorResult->description = "Exception: " . get_class($e) . ", API: $apiCall, Code: " . $e->getCode() . ", Message: " . $e->getMessage();
}
catch(BorhanClientException $ce)
{
	$monitorResult->executionTime = microtime(true) - $start;
	
	$error = new BorhanMonitorError();
	$error->code = $ce->getCode();
	$error->description = $ce->getMessage();
	$error->level = BorhanMonitorError::CRIT;
	
	$monitorResult->errors[] = $error;
	$monitorResult->description = "Exception: " . get_class($ce) . ", API: $apiCall, Code: " . $ce->getCode() . ", Message: " . $ce->getMessage();
}
catch(Exception $ex)
{
	$monitorResult->executionTime = microtime(true) - $start;
	
	$error = new BorhanMonitorError();
	$error->code = $ex->getCode();
	$error->description = $ex->getMessage();
	$error->level = BorhanMonitorError::ERR;
	
	$monitorResult->errors[] = $error;
	$monitorResult->description = $ex->getMessage();
}

echo "$monitorResult";
exit(0);

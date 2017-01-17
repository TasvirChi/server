<?php
$config = array();
$client = null;
$serviceUrl = null;
/* @var $client BorhanClient */
require_once __DIR__  . '/common.php';

$options = getopt('', array(
	'service-url:',
	'debug',
	'entry-id:',
	'entry-reference-id:',
));

$start = microtime(true);
$monitorResult = new BorhanMonitorResult();
$apiCall = null;
try
{
	$apiCall = 'session.start';
	$ks = $client->session->start($config['monitor-partner']['adminSecret'], 'monitor-user', BorhanSessionType::ADMIN, $config['monitor-partner']['id']);
	$client->setKs($ks);
	
	$entry = null;
	/* @var $entry BorhanMediaEntry */
	if(isset($options['entry-id']))
	{
		$apiCall = 'media.get';
		$entry = $client->media->get($options['entry-id']);
	}
	elseif(isset($options['entry-reference-id']))
	{
		$apiCall = 'baseEntry.listByReferenceId';
		$baseEntryList = $client->baseEntry->listByReferenceId($options['entry-reference-id']);
		/* @var $baseEntryList BorhanBaseEntryListResponse */
		if(!count($baseEntryList->objects))
			throw new Exception("Entry with reference id [" . $options['entry-reference-id'] . "] not found");
			
		$entry = reset($baseEntryList->objects);
	}
	
	if($entry->status != BorhanEntryStatus::READY)
		throw new Exception("Entry id [$entry->id] is not ready for thumbnail capturing");
	
	$thumbParams = new BorhanThumbParams();
	$thumbParams->videoOffset = 3;
	
	$apiCall = 'thumbAsset.generate';
	$thumbAsset = $client->thumbAsset->generate($entry->id, $thumbParams);
	/* @var $thumbAsset BorhanThumbAsset */
	if(!$thumbAsset)
		throw new Exception("thumbnail asset not created");
	
	$monitorResult->executionTime = microtime(true) - $start;
	$monitorResult->value = $monitorResult->executionTime;
	
	if($thumbAsset->status == BorhanThumbAssetStatus::READY || $thumbAsset->status == BorhanThumbAssetStatus::EXPORTING)
	{
		$monitorResult->description = "capture time: $monitorResult->executionTime seconds";
	}
	elseif($thumbAsset->status == BorhanThumbAssetStatus::ERROR)
	{
		$error = new BorhanMonitorError();
		$error->description = "captura failed, asset id, $thumbAsset->id: $thumbAsset->description";
		$error->level = BorhanMonitorError::CRIT;
		
		$monitorResult->description = "captura failed, asset id, $thumbAsset->id";
	}
	else
	{
		$error = new BorhanMonitorError();
		$error->description = "unexpected thumbnail status, $thumbAsset->status, asset id, $thumbAsset->id: $thumbAsset->description";
		$error->level = BorhanMonitorError::CRIT;
		
		$monitorResult->errors[] = $error;
		$monitorResult->description = "unexpected thumbnail status, $thumbAsset->status, asset id, $thumbAsset->id: $thumbAsset->description";
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

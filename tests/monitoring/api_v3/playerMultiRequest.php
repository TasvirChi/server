<?php
$config = array();
$client = null;
/* @var $client BorhanClient */
require_once __DIR__  . '/common.php';

$options = getopt('', array(
	'service-url:',
	'debug',
	'entry-id:',
	'entry-reference-id:',
	'list-flavors',
	'list-cue-points',
	'list-metadata',
));

if(!isset($options['entry-id']) && !isset($options['entry-reference-id']))
{
	echo "One of arguments entry-id or entry-reference-id is required";
	exit(-1);
}

$start = microtime(true);
$monitorResult = new BorhanMonitorResult();
$apiCall = null;
try
{
	$apiCall = 'session.start';
	$ks = $client->session->start($config['monitor-partner']['secret'], 'monitor-user', BorhanSessionType::USER, $config['monitor-partner']['id']);
	$client->setKs($ks);
		
	$entryId = null;

	$contextDataParams = new BorhanEntryContextDataParams();
	$contextDataParams->streamerType = 'http';
	
	$client->startMultiRequest();

	if(isset($options['entry-id']))
	{
		$entry = $client->baseEntry->get($options['entry-id']);
		/* @var $entry BorhanMediaEntry */
	}
	elseif(isset($options['entry-reference-id']))
	{
		$baseEntryList = $client->baseEntry->listByReferenceId($options['entry-reference-id']);
		/* @var $baseEntryList BorhanBaseEntryListResponse */
		$entry = $baseEntryList->objects[0];
		/* @var $entry BorhanMediaEntry */
	}
	
	$client->baseEntry->getContextData($entry->id, $contextDataParams);
	
	if(isset($options['list-flavors']))
	{
		$flavorAssetFilter = new BorhanFlavorAssetFilter();
		$flavorAssetFilter->entryIdEqual = $entry->id;
		$flavorAssetFilter->statusEqual = BorhanFlavorAssetStatus::READY;
		$client->flavorAsset->listAction($flavorAssetFilter);
	}
	
	if(isset($options['list-cue-points']))
	{
		$cuePointFilter = new BorhanCuePointFilter();
		$cuePointFilter->entryIdEqual = $entry->id;
		$cuePointFilter->statusEqual = BorhanCuePointStatus::READY;
		$cuePointPlugin = BorhanCuePointClientPlugin::get($client);
		$cuePointPlugin->cuePoint->listAction($cuePointFilter);
	}
	
	if(isset($options['list-metadata']))
	{
		$metadataFilter = new BorhanMetadataFilter();
		$metadataFilter->entryIdEqual = $entry->id;
		$metadataFilter->statusEqual = BorhanMetadataStatus::VALID;
		$metadataPlugin = BorhanMetadataClientPlugin::get($client);
		$metadataPlugin->metadata->listAction($metadataFilter);
	}

	$requestStart = microtime(true);
	$apiCall = 'multi-request';
	$responses = $client->doMultiRequest();
	$requestEnd = microtime(true);
	
	foreach($responses as $response)
	{
		if(is_array($response) && isset($response['message']) && isset($response['code']))
			throw new BorhanException($response["message"], $response["code"]);
	}
	
	$monitorResult->executionTime = $requestEnd - $start;
	$monitorResult->value = $requestEnd - $requestStart;
	$monitorResult->description = "Multi-request execution time: $monitorResult->value seconds";
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

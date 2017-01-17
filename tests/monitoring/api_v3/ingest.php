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
	'conversion-profile-id:',
	'conversion-profile-system-name:',
	'use-single-resource',
	'use-multi-request',
));

if(!isset($options['timeout']))
{
	echo "Argument timeout is required";
	exit(-1);
}
$timeout = $options['timeout'];

if(!isset($options['conversion-profile-id']) && !isset($options['conversion-profile-system-name']))
{
	echo "One of arguments conversion-profile-id or conversion-profile-system-name is required";
	exit(-1);
}

$start = microtime(true);
$monitorResult = new BorhanMonitorResult();
$apiCall = null;
try
{
	$conversionProfileId = null;
	/* @var $entry BorhanMediaEntry */
	if(isset($options['conversion-profile-id']))
	{
		$conversionProfileId = $options['conversion-profile-id'];
	}
	elseif(isset($options['conversion-profile-system-name']))
	{
		$apiCall = 'session.start';
		$ks = $client->session->start($config['monitor-partner']['adminSecret'], 'monitor-user', BorhanSessionType::ADMIN, $config['monitor-partner']['id']);
		$client->setKs($ks);
			
		$conversionProfileFilter = new BorhanConversionProfileFilter();
		$conversionProfileFilter->systemNameEqual = $options['conversion-profile-system-name'];
		
		$apiCall = 'conversionProfile.list';
		$conversionProfileList = $client->conversionProfile->listAction($conversionProfileFilter);
		/* @var $conversionProfileList BorhanConversionProfileListResponse */
		if(!count($conversionProfileList->objects))
			throw new Exception("conversion profile with system name [" . $options['conversion-profile-system-name'] . "] not found");
			
		$conversionProfile = reset($conversionProfileList->objects);
		/* @var $conversionProfile BorhanConversionProfile */
		$conversionProfileId = $conversionProfile->id;
	}

	$apiCall = 'session.start';
	$ks = $client->session->start($config['monitor-partner']['secret'], 'monitor-user', BorhanSessionType::USER, $config['monitor-partner']['id']);
	$client->setKs($ks);
	
	$flavors = array(
		0 => __DIR__ . '/media/source.mp4',
		1 => __DIR__ . '/media/flavor1.3gp',
		2 => __DIR__ . '/media/flavor2.mp4',
		3 => __DIR__ . '/media/flavor3.mp4',
	);
	
	if(isset($options['use-multi-request']))
		$client->startMultiRequest();
		
	 // Creates a new entry
	$entry = new BorhanMediaEntry();
	$entry->name = 'monitor-test';
	$entry->description = 'monitor-test';
	$entry->mediaType = BorhanMediaType::VIDEO;
	
	$apiCall = 'media.add';
	$createdEntry = $client->media->add($entry);
	/* @var $createdEntry BorhanMediaEntry */
	
	$resources = array();
	foreach($flavors as $assetParamsId => $filePath)
	{
		$uploadToken = new BorhanUploadToken();
		$uploadToken->fileName = basename($filePath);
		$uploadToken->fileSize = filesize($filePath);
		
		$createdToken = $client->uploadToken->add($uploadToken);
		/* @var $createdToken BorhanUploadToken */
		$uploadedToken = $client->uploadToken->upload($createdToken->id, $filePath);
		/* @var $uploadedToken BorhanUploadToken */
		
		$contentResource = new BorhanUploadedFileTokenResource();
		$contentResource->token = $uploadedToken->id;
		
		$resources[$assetParamsId] = $contentResource;
	}
	
	if(isset($options['use-single-resource']))
	{
		$resource = new BorhanAssetsParamsResourceContainers();
		$resource->resources = array();
		
		foreach($resources as $assetParamsId => $contentResource)
		{
			$flavorResource = new BorhanAssetParamsResourceContainer();
			$flavorResource->assetParamsId = $assetParamsId;
			$flavorResource->resource = $contentResource;
			
			$resource->resources[] = $flavorResource;
		}
		$client->media->addContent($createdEntry->id, $resource);
	}
	else
	{
		foreach($resources as $flavorParamsId => $contentResource)
		{
			$flavorAsset = new BorhanFlavorAsset();
			$flavorAsset->flavorParamsId = $flavorParamsId;
			$createdAsset = $client->flavorAsset->add($createdEntry->id, $flavorAsset);
			/* @var $createdAsset BorhanFlavorAsset */
			
			$client->flavorAsset->setContent($createdAsset->id, $contentResource);
		}
	}
	// Waits for the entry to start conversion
	$apiCall = 'media.get';
	$createdEntry = $client->media->get($createdEntry->id);
	
	if(isset($options['use-multi-request']))
	{
		$apiCall = 'multirequest';
		$results = $client->doMultiRequest();
		foreach($results as $index => $result)
		{
			if ($client->isError($result))
				throw new BorhanException($result["message"], $result["code"]);
		}
		
		$createdEntry = end($results);
	}
	
	$timeoutTime = time() + $timeout;
	/* @var $createdEntry BorhanMediaEntry */
	while ($createdEntry)
	{
		if(time() > $timeoutTime)
			throw new Exception("timed out, entry id: $createdEntry->id");
			
		if($createdEntry->status == BorhanEntryStatus::PRECONVERT)
		{
			sleep(1);
			$apiCall = 'media.get';
			$createdEntry = $client->media->get($createdEntry->id);
			continue;
		}
		
		$monitorResult->executionTime = microtime(true) - $start;
		$monitorResult->value = $monitorResult->executionTime;
		
		if($createdEntry->status == BorhanEntryStatus::READY)
		{
			$monitorResult->description = "ingestion time: $monitorResult->executionTime seconds";
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

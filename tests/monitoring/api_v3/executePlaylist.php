<?php
$config = array();
$client = null;
/* @var $client BorhanClient */
require_once __DIR__  . '/common.php';

$options = getopt('', array(
	'service-url:',
	'debug',
	'playlist-id:',
	'playlist-reference-id:',
));

if(!isset($options['playlist-id']) && !isset($options['playlist-reference-id']))
{
	echo "One of arguments playlist-id or playlist-reference-id is required";
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
		
	$playlistId = null;
	if(isset($options['playlist-id']))
	{
		$playlistId = $options['playlist-id'];
	}
	elseif(isset($options['playlist-reference-id']))
	{
		$apiCall = 'baseEntry.listByReferenceId';
		$baseEntryList = $client->baseEntry->listByReferenceId($options['playlist-reference-id']);
		/* @var $baseEntryList BorhanBaseEntryListResponse */
		if(count($baseEntryList->objects))
		{
			$playlist = reset($baseEntryList->objects);
			/* @var $playlist BorhanPlaylist */
			$playlistId = $playlist->id;
		}
		else
		{
			$error = new BorhanMonitorError();
			$error->level = BorhanMonitorError::ERR;
			$error->description = "Playlist with reference id [" . $options['playlist-reference-id'] . "] not found";
			$error->level = BorhanMonitorError::CRIT;
			$monitorResult->errors[] = $error;
		}
	}

	if($playlistId)
	{
		$playlistStart = microtime(true);
		$apiCall = 'playlist.execute';
		$client->playlist->execute($playlistId);
		$playlistEnd = microtime(true);
		
		$monitorResult->executionTime = $playlistEnd - $start;
		$monitorResult->value = $playlistEnd - $playlistStart;
		$monitorResult->description = "Playlist execution time: $monitorResult->value seconds";
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

<?php
$config = null;
$clientConfig = null;
/* @var $clientConfig BorhanConfiguration */

require_once __DIR__ . '/lib/init.php';

$bmcUrl = $clientConfig->serviceUrl . 'bmc';
$bmcHtmlContent = file_get_contents($bmcUrl);
if(!$bmcHtmlContent)
{
	echo "Fetching URL [$bmcUrl] failed\n";
	exit(-1);
}

$swfPaths = array(
	'/flash/bmc/login/' . $config['bmc']['login_version'] . '/login.swf',
	'/flash/bmc/' . $config['bmc']['version'] . '/bmc.swf',
);

foreach($swfPaths as $swfPath)
{
	$url = $clientConfig->serviceUrl . $swfPath;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	$content = curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($code != 200)
	{
		echo "Fetching URL [$url] failed with HTTP error code [$code]\n";
		exit(-1);
	}
	
	$lines = explode("\r\n", $content);
	$headers = array();
	foreach($lines as $line)
	{
		if(!strstr($line, ':'))
			continue;
			
		list($name, $value) = explode(':', $line, 2);
		$headers[trim(strtolower($name))] = trim($value);
	}
	
	if(!isset($headers['content-length']) || !$headers['content-length'])
	{
		echo "Fetching URL [$url] failed, no content returned\n";
		exit(-1);
	}
	
	if(!isset($headers['content-type']) || $headers['content-type'] != 'application/x-shockwave-flash')
	{
		echo "Fetching URL [$url] failed, wrong content type [" . $headers['content-type'] . "]\n";
		exit(-1);
	}
}

exit(0);
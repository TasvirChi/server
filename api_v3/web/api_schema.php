<?php

require_once(__DIR__ . "/../bootstrap.php");

$root = myContentStorage::getFSContentRootPath();
$outputPathBase = "$root/content/clientlibs";

$fileLocation = "$outputPathBase/BorhanClient.xml";

if (!file_exists($fileLocation))
	die("BorhanClient.xml was not found");
	
header("Content-Type: text/xml");
readfile($fileLocation);

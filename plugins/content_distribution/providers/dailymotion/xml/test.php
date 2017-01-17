<?php

define('BORHAN_ROOT_PATH', realpath(dirname(__FILE__) . '/../../../../..'));
require_once(BORHAN_ROOT_PATH . '/infra/KAutoloader.php');

define("BORHAN_API_PATH", BORHAN_ROOT_PATH . "/api_v3");

require_once(BORHAN_ROOT_PATH . '/alpha/config/kConf.php');
// Autoloader
require_once(BORHAN_ROOT_PATH.DIRECTORY_SEPARATOR."infra".DIRECTORY_SEPARATOR."KAutoloader.php");
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "propel", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_API_PATH, "lib", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_API_PATH, "services", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "alpha", "plugins", "*")); // needed for testmeDoc
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "plugins", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "generator")); // needed for testmeDoc
KAutoloader::setClassMapFilePath(kConf::get("cache_root_path") . '/plugins/' . basename(__FILE__) . '.cache');
//KAutoloader::dumpExtra();
KAutoloader::register();

// Timezone
date_default_timezone_set(kConf::get("date_default_timezone")); // America/New_York

error_reporting(E_ALL);
BorhanLog::setLogger(new BorhanStdoutLogger());

$dbConf = kConf::getDB();
DbManager::setConfig($dbConf);
DbManager::initialize();

kCurrentContext::$ps_vesion = 'ps3';

$entryId = '0_bs1fapzx';

/*$matches = null;
if (preg_match ( "/x0y.*.err/" , '/pub/in/x0y.title.err' , $matches))
{
	print_r($matches);
	print_r(preg_split ("/\./", $matches[0]));
}
else
{
 echo 'non';
}
return;
if(isset($argv[1]))
	$entryId = $argv[1];

foreach($argv as $arg)
{
	$matches = null;
	if(preg_match('/(.*)=(.*)/', $arg, $matches))
	{
		$field = $matches[1];
//		$providerData->$field = $matches[2];
	}
}

		$fileTransferMgr = kFileTransferMgr::getInstance(kFileTransferMgrType::FTP);
		if(!$fileTransferMgr)
			throw new Exception("SFTP manager not loaded");
			
		$fileTransferMgr->login('ftp-int.vzw.real.com', 'vp_foxsports', 'X4ul3ap');
		print_r($fileTransferMgr->listDir("/pub/in"));
//		$fileTransferMgr->putFile($destFile, $srcFile, true);

		return;*/
$entry = entryPeer::retrieveByPKNoFilter($entryId);
$mrss = kMrssManager::getEntryMrss($entry);
file_put_contents('mrss.xml', $mrss);
BorhanLog::debug("MRSS [$mrss]");

$distributionJobData = new BorhanDistributionSubmitJobData();

$dbDistributionProfile = DistributionProfilePeer::retrieveByPK(3);
$distributionProfile = new BorhanDailymotionDistributionProfile();
$distributionProfile->fromObject($dbDistributionProfile);
$distributionJobData->distributionProfileId = $distributionProfile->id;


$distributionJobData->distributionProfile = $distributionProfile;

$dbEntryDistribution = EntryDistributionPeer::retrieveByPK(24);
$entryDistribution = new BorhanEntryDistribution();
$entryDistribution->fromObject($dbEntryDistribution);
$distributionJobData->entryDistributionId = $entryDistribution->id;
$distributionJobData->entryDistribution = $entryDistribution;

$myp = new DailymotionDistributionProfile();
print_r($myp->validateForSubmission($dbEntryDistribution, "submit"));


$providerData = new BorhanDailymotionDistributionJobProviderData($distributionJobData);
$distributionJobData->providerData = $providerData;

//file_put_contents('out.xml', $providerData->xml);
//BorhanLog::debug("XML [$providerData->xml]");

//return;
$engine = new DailymotionDistributionEngine();
$engine->submit($distributionJobData);


//$xml = new KDOMDocument();
//if(!$xml->loadXML($mrss))
//{
//	BorhanLog::err("MRSS not is not valid XML:\n$mrss\n");
//	exit;
//}
//
//$xslPath = 'submit.xsl';
//$xsl = new KDOMDocument();
//$xsl->load($xslPath);
//			
//// set variables in the xsl
//$varNodes = $xsl->getElementsByTagName('variable');
//foreach($varNodes as $varNode)
//{
//	$nameAttr = $varNode->attributes->getNamedItem('name');
//	if(!$nameAttr)
//		continue;
//		
//	$name = $nameAttr->value;
//	if($name && $distributionJobData->$name)
//	{
//		$varNode->textContent = $distributionJobData->$name;
//		$varNode->appendChild($xsl->createTextNode($distributionJobData->$name));
//		BorhanLog::debug("Set variable [$name] to [{$distributionJobData->$name}]");
//	}
//}
//
//$proc = new XSLTProcessor;
//$proc->registerPHPFunctions();
//$proc->importStyleSheet($xsl);
//
//$xml = $proc->transformToDoc($xml);
//if(!$xml)
//{
//	BorhanLog::err("Transform returned false");
//	exit;
//}
//
//$xml = $xml->saveXML();
//
//file_put_contents('out.xml', $xml);
//BorhanLog::debug("XML [$xml]");

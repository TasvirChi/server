 <?php

ini_set("memory_limit","256M");

require_once(__DIR__ . '/bootstrap.php');

if (!$argc)
{
	die('pleas provide partner id as input' . PHP_EOL . 
		'to run script: ' . basename(__FILE__) . ' X' . PHP_EOL . 
		'whereas X is partner id' . PHP_EOL);
}

$partnerId = $argv[0];

$dbConf = kConf::getDB();
DbManager::setConfig ( $dbConf );
DbManager::initialize ();

$c = new Criteria();
$c->add(uiConfPeer::SWF_URL, "%bcw%",Criteria::LIKE);
$c->add(uiConfPeer::OBJ_TYPE , uiConf::UI_CONF_TYPE_CW, Criteria::EQUAL);
$c->add(uiConfPeer::PARTNER_ID, $partnerId, Criteria::EQUAL);

$bcwUiconfs = uiConfPeer::doSelect($c);


if (!count($bcwUiconfs))
{
	exit;
}

$fileName = "/manual_uiconfs_paths.log";
$flog = fopen($fileName,'a+');
//Run a loop for each uiConf to get its filesync key, thus acquiring its confile
foreach ($bcwUiconfs as $bcwUiconf)
{
	/* @var $bcwUiconf uiConf */
	$bcwUiconfFilesyncKey = $bcwUiconf->getSyncKey(uiConf::FILE_SYNC_UICONF_SUB_TYPE_DATA);
	$bcwConfile = kFileSyncUtils::file_get_contents($bcwUiconfFilesyncKey, false , false);
	
	if (!$bcwConfile)
	{
		continue;
	}
		
	$bcwConfileXML = new SimpleXMLElement($bcwConfile);

	$path = '//provider[@id="borhan" or @name="borhan"]';
	
	$nodesToRemove = $bcwConfileXML->xpath($path);
	
	if (!count($nodesToRemove))
	{
		continue;
	}
	
	
	if ($bcwUiconf->getCreationMode() != uiConf::UI_CONF_CREATION_MODE_MANUAL)
	{
		//No point in this "for" loop if we can't save the UIConf.
		foreach ($nodesToRemove as $nodeToRemove)
		{
			$nodeToRemoveDom = dom_import_simplexml($nodeToRemove);

			$nodeToRemoveDom->parentNode->removeChild($nodeToRemoveDom);
		}
		$bcwConfile = $bcwConfileXML->saveXML();
		$bcwUiconf->setConfFile($bcwConfile);
		$bcwUiconf->save();
	}
	else
	{
		$confilePath = $bcwUiconf->getConfFilePath()."\n";
		fwrite($flog, $confilePath);
	}
	//$bcw_uiconf_filesync_key = $bcw_uiconf->getSyncKey(uiConf::FILE_SYNC_UICONF_SUB_TYPE_DATA);
	//kFileSyncUtils::file_put_contents($bcw_uiconf_filesync_key, $bcw_confile , false);
}
fclose($flog);

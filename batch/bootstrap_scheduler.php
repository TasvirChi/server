<?php
/**
 * 
 * @package Scheduler
 */

chdir(__DIR__);
define('BORHAN_ROOT_PATH', realpath(__DIR__ . '/../'));
require_once(BORHAN_ROOT_PATH . '/alpha/config/kConf.php');

define("BORHAN_BATCH_PATH", BORHAN_ROOT_PATH . "/batch");

// Autoloader - override the autoloader defaults
require_once(BORHAN_ROOT_PATH . "/infra/KAutoloader.php");
KAutoloader::setClassPath(array(
	KAutoloader::buildPath(BORHAN_ROOT_PATH, "infra", "*"),
	KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "*"),
	KAutoloader::buildPath(BORHAN_ROOT_PATH, "plugins", "*"),
	KAutoloader::buildPath(BORHAN_BATCH_PATH, "*"),
));

KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "plugins", "*", "batch", "*"));

KAutoloader::setIncludePath(array(
	KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "ZendFramework", "library"),
));
KAutoloader::setClassMapFilePath(kEnvironment::get("cache_root_path") . '/batch/classMap.cache');
KAutoloader::register();

// Logger
$loggerConfigPath = BORHAN_ROOT_PATH . "/configurations/logger.ini";

try // we don't want to fail when logger is not configured right
{
	$config = new Zend_Config_Ini($loggerConfigPath);
	BorhanLog::initLog($config->batch_scheduler);
	BorhanLog::setContext("BATCH");
}
catch(Zend_Config_Exception $ex)
{
}


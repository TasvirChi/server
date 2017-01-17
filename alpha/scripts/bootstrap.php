<?php
set_time_limit(0);

ini_set("memory_limit","700M");

define("BORHAN_ROOT_PATH", realpath(__DIR__ . '/../../'));
require_once(BORHAN_ROOT_PATH . '/alpha/config/kConf.php');
require_once(BORHAN_ROOT_PATH . '/infra/KAutoloader.php');

KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "infra", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "plugins", "*"));
KAutoloader::setClassMapFilePath(kConf::get("cache_root_path") . '/scripts/classMap.cache');
KAutoloader::addExcludePath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "aws", "*")); // Do not load AWS files
KAutoloader::addExcludePath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "HTMLPurifier", "*")); // Do not load HTMLPurifier files
KAutoloader::register();

date_default_timezone_set(kConf::get("date_default_timezone"));

$loggerConfigPath = BORHAN_ROOT_PATH.'/configurations/logger.ini';
try
{
	$config = new Zend_Config_Ini($loggerConfigPath);
	BorhanLog::initLog($config->scripts);
	BorhanLog::setContext(basename($_SERVER['SCRIPT_NAME']));
}
catch (Zend_Config_Exception $ex)
{
	
}
BorhanLog::info("Starting script");

BorhanLog::info("Initializing database...");
DbManager::setConfig(kConf::getDB());
DbManager::initialize();
BorhanLog::info("Database initialized successfully");

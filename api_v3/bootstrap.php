<?php

if (!defined("BORHAN_ROOT_PATH"))			// may already be defined when invoked through bwidgetAction
	define("BORHAN_ROOT_PATH", realpath(__DIR__ . '/../'));
if (!defined("SF_ROOT_DIR"))				// may already be defined when invoked through bwidgetAction
	define('SF_ROOT_DIR', BORHAN_ROOT_PATH . '/alpha');
define("BORHAN_API_V3", true); // used for different logic in alpha libs

define("BORHAN_API_PATH", BORHAN_ROOT_PATH.DIRECTORY_SEPARATOR."api_v3");
require_once(BORHAN_API_PATH.DIRECTORY_SEPARATOR.'VERSION.php'); //defines BORHAN_API_VERSION
require_once (BORHAN_ROOT_PATH.DIRECTORY_SEPARATOR.'alpha'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'kConf.php');


// Autoloader
require_once(BORHAN_ROOT_PATH.DIRECTORY_SEPARATOR."infra".DIRECTORY_SEPARATOR."KAutoloader.php");
KAutoloader::setClassMapFilePath(kConf::get("cache_root_path") . '/api_v3/classMap.cache');
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "propel", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "vendor", "nusoap", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_API_PATH, "lib", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_API_PATH, "services", "*"));
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "alpha", "plugins", "*")); // needed for testmeDoc
KAutoloader::addClassPath(KAutoloader::buildPath(BORHAN_ROOT_PATH, "plugins", "*"));
KAutoloader::register();


// Timezone
date_default_timezone_set(kConf::get("date_default_timezone")); // America/New_York

// Logger
kLoggerCache::InitLogger('api_v3');
BorhanLog::setContext("API");

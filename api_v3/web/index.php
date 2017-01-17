<?php
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Range, Cache-Control');
	header('Access-Control-Allow-Methods: POST, GET, HEAD, OPTIONS');
	header('Access-Control-Expose-Headers: Server, Content-Length, Content-Range, Date');
	exit;
}

$start = microtime(true);
// check cache before loading anything
require_once(dirname(__FILE__)."/../lib/BorhanResponseCacher.php");
$cache = new BorhanResponseCacher();
$cache->checkOrStart();

require_once(dirname(__FILE__)."/../bootstrap.php");

// Database
DbManager::setConfig(kConf::getDB());
DbManager::initialize();

ActKeyUtils::checkCurrent();

BorhanLog::debug(">------------------------------------- api_v3 -------------------------------------");
BorhanLog::info("API-start pid:".getmypid());

$controller = BorhanFrontController::getInstance();
$result = $controller->run();

$end = microtime(true);
BorhanLog::info("API-end [".($end - $start)."]");
BorhanLog::debug("<------------------------------------- api_v3 -------------------------------------");

$cache->end($result);

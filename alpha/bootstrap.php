<?php

define('BORHAN_ROOT_PATH',				realpath(__DIR__ . '/../'));

define('SF_APP',						'borhan');
define('SF_ROOT_DIR',					BORHAN_ROOT_PATH . '/alpha');
define('MODULES', 						SF_ROOT_DIR . '/apps/borhan/modules/');


$sf_symfony_lib_dir = BORHAN_ROOT_PATH . '/vendor/symfony';
$sf_symfony_data_dir = BORHAN_ROOT_PATH . '/vendor/symfony-data';

// symfony bootstraping
require_once("$sf_symfony_lib_dir/util/sfCore.class.php");
sfCore::bootstrap($sf_symfony_lib_dir, $sf_symfony_data_dir);

// Logger
kLoggerCache::InitLogger(BORHAN_LOG, 'PS2');

sfLogger::getInstance()->registerLogger(BorhanLog::getInstance());
sfLogger::getInstance()->setLogLevel(7);
sfConfig::set('sf_logging_enabled', true);

DbManager::setConfig(kConf::getDB());
DbManager::initialize();

ActKeyUtils::checkCurrent();
sfContext::getInstance()->getController()->dispatch();

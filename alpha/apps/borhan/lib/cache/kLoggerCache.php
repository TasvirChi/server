<?php

/**
 * @package server-infra
 * @subpackage cache
 */
class kLoggerCache
{
	const LOGGER_APC_CACHE_KEY_PREFIX = 'LoggerInstance_';

	/**
	 * @param $configName string
	 * @param $context string
	 */
	static public function InitLogger($configName, $context = null)
	{
		if (BorhanLog::getLogger())	// already initialized
			return;
		
		if (function_exists('apc_fetch'))
		{
			$cacheKey = self::LOGGER_APC_CACHE_KEY_PREFIX . $configName;
			$logger = apc_fetch($cacheKey);
			if ($logger)
			{
				list($logger, $cacheVersionId) = $logger;
				if ($cacheVersionId == kConf::getCachedVersionId())
				{
					BorhanLog::setLogger($logger);
					return;
				}
			}
		}

		try // we don't want to fail when logger is not configured right
		{
			$config = new Zend_Config(kConf::getMap('logger'));
			
			BorhanLog::initLog($config->$configName);
			if ($context)
				BorhanLog::setContext($context);
					
			if (function_exists('apc_store'))
				apc_store($cacheKey, array(BorhanLog::getLogger(), kConf::getCachedVersionId()));
		}
		catch(Zend_Config_Exception $ex)
		{
		}
	}
}

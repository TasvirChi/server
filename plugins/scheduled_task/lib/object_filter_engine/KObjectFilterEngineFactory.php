<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectFilterEngine
 */
class KObjectFilterEngineFactory
{
	/**
	 * @param $type
	 * @param BorhanClient $client
	 * @return KObjectFilterEngineBase
	 */
	public static function getInstanceByType($type, BorhanClient $client)
	{
		switch($type)
		{
			case BorhanObjectFilterEngineType::ENTRY:
				return new KObjectFilterBaseEntryEngine($client);
			default:
				return BorhanPluginManager::loadObject('KObjectFilterEngineBase', $type, array($client));
		}
	}
} 
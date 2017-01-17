<?php

/**
 * @package plugins.queue
 */
class QueuePlugin extends BorhanPlugin implements IBorhanVersion, IBorhanRequire
{
	const PLUGIN_NAME = 'queue';
	const PLUGIN_VERSION_MAJOR = 1;
	const PLUGIN_VERSION_MINOR = 0;
	const PLUGIN_VERSION_BUILD = 0;
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanVersion::getVersion()
	 */
	public static function getVersion()
	{
		return new BorhanVersion(
			self::PLUGIN_VERSION_MAJOR,
			self::PLUGIN_VERSION_MINOR,
			self::PLUGIN_VERSION_BUILD
		);		
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanRequire::requires()
	 */	
	public static function requires()
	{
	    return array("IBorhanQueuePlugin");
	}
}

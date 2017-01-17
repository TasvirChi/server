<?php

/**
 * @package plugins.widevine
 * @subpackage model.enum
 */ 
class WidevineBatchJobType implements IBorhanPluginEnum, BatchJobType
{
	const WIDEVINE_REPOSITORY_SYNC = 'WidevineRepositorySync';
	
	public static function getAdditionalValues()
	{
		return array(
			'WIDEVINE_REPOSITORY_SYNC' => self::WIDEVINE_REPOSITORY_SYNC,
		);
	}
	
	/**
	 * @return array
	 */
	public static function getAdditionalDescriptions()
	{
		return array();
	}
}

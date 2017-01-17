<?php

/*
* @package plugins.widevine
* @subpackage model.enums
*/

class WidevineSchemeName implements IBorhanPluginEnum, DrmSchemeName
{
	const WIDEVINE = 'WIDEVINE';

	public static function getAdditionalValues()
	{
		return array
		(
			'WIDEVINE' => self::WIDEVINE,
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
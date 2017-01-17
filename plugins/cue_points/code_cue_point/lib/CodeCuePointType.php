<?php
/**
 * @package plugins.codeCuePoint
 * @subpackage lib.enum
 */
class CodeCuePointType implements IBorhanPluginEnum, CuePointType
{
	const CODE = 'Code';
	
	public static function getAdditionalValues()
	{
		return array(
			'CODE' => self::CODE,
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

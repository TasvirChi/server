<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanMediaParserType extends BorhanDynamicEnum implements mediaParserType
{
	public static function getEnumClass()
	{
		return 'mediaParserType';
	}
}

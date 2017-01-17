<?php
/**
 * @package plugins.externalMedia
 * @subpackage api.enum
 */
class BorhanExternalMediaSourceType extends BorhanDynamicEnum implements ExternalMediaSourceType
{
	public static function getEnumClass()
	{
		return 'ExternalMediaSourceType';
	}
}

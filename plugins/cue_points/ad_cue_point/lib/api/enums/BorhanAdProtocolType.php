<?php
/**
 * @package plugins.adCuePoint
 * @subpackage api.enum
 */
class BorhanAdProtocolType extends BorhanDynamicEnum implements AdProtocolType
{
	public static function getEnumClass()
	{
		return 'AdProtocolType';
	}
}
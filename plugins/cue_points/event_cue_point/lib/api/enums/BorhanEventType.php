<?php
/**
 * @package plugins.eventCuePoint
 * @subpackage api.enum
 */
class BorhanEventType extends BorhanDynamicEnum implements EventType
{
	public static function getEnumClass()
	{
		return 'EventType';
	}
}
<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanModerationFlagStatus extends BorhanDynamicEnum implements moderationFlagStatus
{
	public static function getEnumClass()
	{
		return 'moderationFlagStatus';
	}
}
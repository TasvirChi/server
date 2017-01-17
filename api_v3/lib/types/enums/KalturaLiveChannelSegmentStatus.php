<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanLiveChannelSegmentStatus extends BorhanDynamicEnum implements LiveChannelSegmentStatus
{
	public static function getEnumClass()
	{
		return 'LiveChannelSegmentStatus';
	}
}
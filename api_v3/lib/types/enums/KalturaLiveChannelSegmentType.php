<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanLiveChannelSegmentType extends BorhanDynamicEnum implements LiveChannelSegmentType
{
	public static function getEnumClass()
	{
		return 'LiveChannelSegmentType';
	}
}
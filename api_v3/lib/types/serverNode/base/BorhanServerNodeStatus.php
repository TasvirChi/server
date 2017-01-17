<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanServerNodeStatus extends BorhanEnum implements ServerNodeStatus
{
	public static function getEnumClass()
	{
		return 'ServerNodeStatus';
	}
}
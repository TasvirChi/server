<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanEntryServerNodeStatus extends BorhanEnum implements EntryServerNodeStatus{

	public static function getEnumClass()
	{
		return 'EntryServerNodeStatus';
	}
}
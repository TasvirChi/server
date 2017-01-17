<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.enum
 */
class BorhanBusinessProcessServerStatus extends BorhanDynamicEnum implements BusinessProcessServerStatus
{
	public static function getEnumClass()
	{
		return 'BusinessProcessServerStatus';
	}
}
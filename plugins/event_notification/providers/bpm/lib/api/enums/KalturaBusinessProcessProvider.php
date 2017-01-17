<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.enum
 * @see BusinessProcessProvider
 */
class BorhanBusinessProcessProvider extends BorhanDynamicEnum implements BusinessProcessProvider
{
	public static function getEnumClass()
	{
		return 'BusinessProcessProvider';
	}
}
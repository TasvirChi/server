<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanDeliveryProfileType extends BorhanDynamicEnum implements DeliveryProfileType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'DeliveryProfileType';
	}
}

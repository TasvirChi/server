<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanMailType extends BorhanDynamicEnum implements MailType
{
	public static function getEnumClass()
	{
		return 'MailType';
	}
}

<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanNullableBoolean extends BorhanEnum
{
	const NULL_VALUE = -1;
	const FALSE_VALUE = 0;
	const TRUE_VALUE = 1;
	
	public static function toBoolean($value)
	{
		switch($value)
		{
			case self::FALSE_VALUE:
				return false;
				
			case self::TRUE_VALUE:
				return true;
				
			default:
				return null;
		}
	}
}
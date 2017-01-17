<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanRuleActionType extends BorhanDynamicEnum implements RuleActionType
{
	public static function getEnumClass()
	{
		return 'RuleActionType';
	}
}
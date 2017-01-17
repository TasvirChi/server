<?php
/**
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanRuleAction extends BorhanObject
{
	/**
	 * The type of the action
	 * 
	 * @readonly
	 * @var BorhanRuleActionType
	 */
	public $type;
}
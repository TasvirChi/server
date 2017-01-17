<?php
/**
 * @package api
 * @subpackage objects
 * @abstract
 * @deprecated use BorhanRuleAction
 */
abstract class BorhanAccessControlAction extends BorhanObject
{
	/**
	 * The type of the access control action
	 * 
	 * @readonly
	 * @var BorhanAccessControlActionType
	 */
	public $type;
}
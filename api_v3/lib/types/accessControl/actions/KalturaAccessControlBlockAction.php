<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanAccessControlBlockAction extends BorhanRuleAction
{
	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = RuleActionType::BLOCK;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kRuleAction(RuleActionType::BLOCK);
			
		return parent::toObject($dbObject, $skip);
	}
}
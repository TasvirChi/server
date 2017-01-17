<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanStorageAddAction extends BorhanRuleAction
{
	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = RuleActionType::ADD_TO_STORAGE;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kRuleAction(RuleActionType::ADD_TO_STORAGE);
			
		return parent::toObject($dbObject, $skip);
	}
}
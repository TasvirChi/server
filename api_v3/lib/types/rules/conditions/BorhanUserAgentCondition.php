<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUserAgentCondition extends BorhanRegexCondition
{
	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = ConditionType::USER_AGENT;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kUserAgentCondition();
			
		return parent::toObject($dbObject, $skip);
	}
}

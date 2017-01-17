<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanUserAgentRestriction extends BorhanBaseRestriction 
{
	/**
	 * User agent restriction type (Allow or deny)
	 * 
	 * @var BorhanUserAgentRestrictionType
	 */
	public $userAgentRestrictionType; 
	
	/**
	 * A comma seperated list of user agent regular expressions
	 * 
	 * @var string
	 */
	public $userAgentRegexList;
	
	private static $mapBetweenObjects = array
	(
		"userAgentRestrictionType",
		"userAgentRegexList",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseRestriction::toRule()
	 */
	public function toRule(BorhanRestrictionArray $restrictions)
	{
		return $this->toObject(new kAccessControlUserAgentRestriction());
	}
}
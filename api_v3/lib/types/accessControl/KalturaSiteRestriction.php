<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanSiteRestriction extends BorhanBaseRestriction 
{
	/**
	 * The site restriction type (allow or deny)
	 * 
	 * @var BorhanSiteRestrictionType
	 */
	public $siteRestrictionType;
	
	/**
	 * Comma separated list of sites (domains) to allow or deny
	 * 
	 * @var string
	 */
	public $siteList;
	
	private static $mapBetweenObjects = array
	(
		"siteRestrictionType",
		"siteList",
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
		return $this->toObject(new kAccessControlSiteRestriction());
	}
}
<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanCountryRestriction extends BorhanBaseRestriction 
{
	/**
	 * Country restriction type (Allow or deny)
	 * 
	 * @var BorhanCountryRestrictionType
	 */
	public $countryRestrictionType; 
	
	/**
	 * Comma separated list of country codes to allow to deny 
	 * 
	 * @var string
	 */
	public $countryList;
	
	private static $mapBetweenObjects = array
	(
		"countryRestrictionType",
		"countryList",
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
		return $this->toObject(new kAccessControlCountryRestriction());
	}
}
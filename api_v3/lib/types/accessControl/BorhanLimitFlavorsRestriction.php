<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanLimitFlavorsRestriction extends BorhanBaseRestriction 
{
	/**
	 * Limit flavors restriction type (Allow or deny)
	 * 
	 * @var BorhanLimitFlavorsRestrictionType
	 */
	public $limitFlavorsRestrictionType; 
	
	/**
	 * Comma separated list of flavor params ids to allow to deny 
	 * 
	 * @var string
	 */
	public $flavorParamsIds;
	
	private static $mapBetweenObjects = array
	(
		"limitFlavorsRestrictionType",
		"flavorParamsIds",
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
		return $this->toObject(new kAccessControlLimitFlavorsRestriction());
	}
}
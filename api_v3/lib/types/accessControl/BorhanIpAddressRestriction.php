<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanIpAddressRestriction extends BorhanBaseRestriction 
{
	/**
	 * Ip address restriction type (Allow or deny)
	 * 
	 * @var BorhanIpAddressRestrictionType
	 */
	public $ipAddressRestrictionType; 
	
	/**
	 * Comma separated list of ip address to allow to deny 
	 * 
	 * @var string
	 */
	public $ipAddressList;
	
	private static $mapBetweenObjects = array
	(
		"ipAddressRestrictionType",
		"ipAddressList",
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
		return $this->toObject(new kAccessControlIpAddressRestriction());
	}
}
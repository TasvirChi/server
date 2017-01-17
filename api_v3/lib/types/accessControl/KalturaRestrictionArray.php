<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRuleArray instead
 */
class BorhanRestrictionArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanRestrictionArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = self::getInstanceByDbObject($obj);
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}

	static function getInstanceByDbObject(kAccessControlRestriction $dbObject)
	{
		$objectClass = get_class($dbObject);
		switch($objectClass)
		{
			case "kAccessControlSiteRestriction":
				return new BorhanSiteRestriction();
			case "kAccessControlCountryRestriction":
				return new BorhanCountryRestriction();
			case "kAccessControlSessionRestriction":
				return new BorhanSessionRestriction();
			case "kAccessControlPreviewRestriction":
				return new BorhanPreviewRestriction();
			case "kAccessControlIpAddressRestriction":
				return new BorhanIpAddressRestriction();
			case "kAccessControlUserAgentRestriction":
				return new BorhanUserAgentRestriction();
			case "kAccessControlLimitFlavorsRestriction":
				return new BorhanLimitFlavorsRestriction();
			default:
				BorhanLog::err("Access control rule type [$objectClass] could not be loaded");
				return null;
		}
	}
	
	public function __construct()
	{
		parent::__construct("BorhanBaseRestriction");	
	}
}
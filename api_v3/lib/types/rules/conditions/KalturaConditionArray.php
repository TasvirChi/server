<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanConditionArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanConditionArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = self::getInstanceByDbObject($obj);
			if(!$nObj)
			{
				BorhanLog::alert("Object [" . get_class($obj) . "] type [" . $obj->getType() . "] could not be translated to API object");
				continue;
			}
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}

	static function getInstanceByDbObject(kCondition $dbObject)
	{
		switch($dbObject->getType())
		{
			case ConditionType::AUTHENTICATED:
				return new BorhanAuthenticatedCondition();
			case ConditionType::COUNTRY:
				return new BorhanCountryCondition();
			case ConditionType::IP_ADDRESS:
				return new BorhanIpAddressCondition();
			case ConditionType::SITE:
				return new BorhanSiteCondition();
			case ConditionType::USER_AGENT:
				return new BorhanUserAgentCondition();
			case ConditionType::FIELD_COMPARE:
				return new BorhanFieldCompareCondition();
			case ConditionType::FIELD_MATCH:
				return new BorhanFieldMatchCondition();
			case ConditionType::ASSET_PROPERTIES_COMPARE:
				return new BorhanAssetPropertiesCompareCondition();
			case ConditionType::USER_ROLE:
				return new BorhanUserRoleCondition();
			case ConditionType::GEO_DISTANCE:
				return new BorhanGeoDistanceCondition();
			case ConditionType::OR_OPERATOR:
			    return new BorhanOrCondition();
			case ConditionType::HASH:
			    return new BorhanHashCondition();
			case ConditionType::DELIVERY_PROFILE:
				return new BorhanDeliveryProfileCondition();
			case ConditionType::ACTIVE_EDGE_VALIDATE:
				return new BorhanValidateActiveEdgeCondition();
			default:
			     return BorhanPluginManager::loadObject('BorhanCondition', $dbObject->getType());
		}
	}
		
	public function __construct()
	{
		parent::__construct("BorhanCondition");	
	}
}
<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanRuleActionArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanRuleActionArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = self::getInstanceByDbObject($obj);
			if(!$nObj)
				throw new kCoreException("No API object found for core object [" . get_class($obj) . "] with type [" . $obj->getType() . "]", kCoreException::OBJECT_API_TYPE_NOT_FOUND);
				
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}

	static function getInstanceByDbObject(kRuleAction $dbObject)
	{
		switch($dbObject->getType())
		{
			case RuleActionType::BLOCK:
				return new BorhanAccessControlBlockAction();
			case RuleActionType::PREVIEW:
				return new BorhanAccessControlPreviewAction();
			case RuleActionType::LIMIT_FLAVORS:
				return new BorhanAccessControlLimitFlavorsAction();
			case RuleActionType::ADD_TO_STORAGE:
				return new BorhanStorageAddAction();	
			case RuleActionType::LIMIT_DELIVERY_PROFILES:
				return new BorhanAccessControlLimitDeliveryProfilesAction();
			case RuleActionType::SERVE_FROM_REMOTE_SERVER:
				return new BorhanAccessControlServeRemoteEdgeServerAction();
			case RuleActionType::REQUEST_HOST_REGEX:
				return new BorhanAccessControlModifyRequestHostRegexAction();
			case RuleActionType::LIMIT_THUMBNAIL_CAPTURE:
				return new BorhanAccessControlLimitThumbnailCaptureAction();
			default:
				return BorhanPluginManager::loadObject('BorhanRuleAction', $dbObject->getType());
		}		
	}
		
	public function __construct()
	{
		parent::__construct("BorhanRuleAction");	
	}
}
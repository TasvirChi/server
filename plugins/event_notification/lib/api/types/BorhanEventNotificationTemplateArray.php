<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventNotificationTemplateArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanEventNotificationTemplateArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = BorhanEventNotificationTemplate::getInstanceByType($obj->getType());
    		if(!$nObj)
    		{
    			BorhanLog::err("Event notification template could not find matching type for [" . $obj->getType() . "]");
    			continue;
    		}
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanEventNotificationTemplate");	
	}
}
<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventNotificationParameterArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanEventNotificationParameterArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$parameterType = get_class($obj);
			switch ($parameterType)
			{
				case 'kEventNotificationParameter':
    				$nObj = new BorhanEventNotificationParameter();
					break;
					
				case 'kEventNotificationArrayParameter':
    				$nObj = new BorhanEventNotificationArrayParameter();
					break;
					
				default:
    				$nObj = BorhanPluginManager::loadObject('BorhanEventNotificationParameter', $parameterType);
			}
			
			if($nObj)
			{
				$nObj->fromObject($obj, $responseProfile);
				$newArr[] = $nObj;
			}
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanEventNotificationParameter");	
	}
}
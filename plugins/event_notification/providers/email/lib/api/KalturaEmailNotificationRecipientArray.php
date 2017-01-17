<?php
/**
 * @package plugins.emailNotification
 * @subpackage api.objects
 */
class BorhanEmailNotificationRecipientArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanEmailNotificationRecipientArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanEmailNotificationRecipient();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanEmailNotificationRecipient");	
	}
}
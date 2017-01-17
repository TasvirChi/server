<?php
/**
 * @package plugins.schedule
 * @subpackage api.objects
 */
class BorhanScheduleEventRecurrenceArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanScheduleEventRecurrenceArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanScheduleEventRecurrence();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanScheduleEventRecurrence");	
	}
}
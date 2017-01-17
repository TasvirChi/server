<?php
/**
 * @package plugins.schedule
 * @subpackage api.objects
 */
class BorhanScheduleResourceArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanScheduleResourceArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$newArr[] = BorhanScheduleResource::getInstance($obj, $responseProfile);
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanScheduleResource");	
	}
}
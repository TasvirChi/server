<?php
/**
 * @package plugins.schedule
 * @subpackage api.objects
 */
class BorhanScheduleEventArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanScheduleEventArray();
		if ($arr == null)
			return $newArr;

		// preload all parents in order to have them in the instance pool
		$parentIds = array();
		foreach ($arr as $obj)
		{
			/* @var $obj ScheduleEvent */
			if($obj->getParentId())
			{
				$parentIds[$obj->getParentId()] = true;
			} 
		}
		if(count($parentIds))
		{
			ScheduleEventPeer::retrieveByPKs(array_keys($parentIds));
		}
		
		foreach ($arr as $obj)
		{
			$newArr[] = BorhanScheduleEvent::getInstance($obj, $responseProfile);
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanScheduleEvent");	
	}
}
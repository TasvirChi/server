<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveChannelArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanLiveChannelArray();
		if ($arr == null)
			return $newArr;
			
		foreach ($arr as $obj)
		{
    		$nObj = BorhanEntryFactory::getInstanceByType($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanLiveChannel");	
	}
}
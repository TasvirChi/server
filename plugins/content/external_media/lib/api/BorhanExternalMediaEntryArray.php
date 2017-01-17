<?php
/**
 * @package plugins.externalMedia
 * @subpackage api.objects
 */
class BorhanExternalMediaEntryArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanExternalMediaEntryArray();
		if($arr == null)
			return $newArr;
		
		foreach($arr as $obj)
		{
    		$nObj = BorhanEntryFactory::getInstanceByType($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanExternalMediaEntry");	
	}
}
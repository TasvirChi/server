<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanDestFileSyncDescriptorArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDestFileSyncDescriptorArray();
		if ($arr == null)
			return $newArr;
		foreach ($arr as $obj)
		{
    		$nObj = new BorhanDestFileSyncDescriptor();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanDestFileSyncDescriptor");	
	}
}

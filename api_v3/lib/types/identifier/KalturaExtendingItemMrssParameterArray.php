<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanExtendingItemMrssParameterArray extends BorhanTypedArray
{
	
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanExtendingItemMrssParameterArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanExtendingItemMrssParameter();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanExtendingItemMrssParameter");
	}
}
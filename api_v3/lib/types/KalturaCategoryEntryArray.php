<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCategoryEntryArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanCategoryEntryArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanCategoryEntry();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanCategoryEntry");
	}
}
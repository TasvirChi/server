<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCategoryUserArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanCategoryUserArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanCategoryUser();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanCategoryUser");
	}
}
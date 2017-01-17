<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCategoryArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanCategoryArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanCategory();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanCategory");
	}
}
?>
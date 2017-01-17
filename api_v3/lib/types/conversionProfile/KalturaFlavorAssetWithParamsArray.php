<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFlavorAssetWithParamsArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanFlavorAssetWithParamsArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanFlavorAssetWithParams();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanFlavorAssetWithParams");	
	}
}
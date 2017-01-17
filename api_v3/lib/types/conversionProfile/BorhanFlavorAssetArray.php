<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFlavorAssetArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanFlavorAssetArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
		    $nObj = BorhanFlavorAsset::getInstance($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanFlavorAsset");	
	}
}
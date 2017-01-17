<?php
/**
 * @package plugins.caption
 * @subpackage api.objects
 */
class BorhanCaptionAssetArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanCaptionAssetArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanCaptionAsset();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanCaptionAsset");	
	}
}
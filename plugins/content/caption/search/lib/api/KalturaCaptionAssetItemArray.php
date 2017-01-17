<?php
/**
 * @package plugins.captionSearch
 * @subpackage api.objects
 */
class BorhanCaptionAssetItemArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanCaptionAssetItemArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanCaptionAssetItem();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanCaptionAssetItem");	
	}
}
<?php
/**
 * @package plugins.caption
 * @subpackage api.objects
 */
class BorhanCaptionParamsArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanCaptionParamsArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = BorhanAssetParamsFactory::getAssetParamsInstance($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanCaptionParams");	
	}
}
<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveStreamParamsArray extends BorhanTypedArray {
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanLiveStreamParamsArray();
		if ($arr == null)
			return $newArr;
	
		foreach ($arr as $obj)
		{
			$nObj = new BorhanLiveStreamParams();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
	
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanLiveStreamParams");
	}
}
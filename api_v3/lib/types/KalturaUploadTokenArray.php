<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUploadTokenArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanUploadTokenArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanUploadToken();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanUploadToken");
	}
}

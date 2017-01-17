<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveStreamBitrateArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanLiveStreamBitrateArray();
		if ($arr == null)
			return $newArr;
			
		foreach ($arr as $obj)
		{
			$nObj = new BorhanLiveStreamBitrate();
			$nObj->fromArray($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanLiveStreamBitrate");	
	}
}

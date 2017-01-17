<?php
/**
 * @package plugins.playReady
 * @subpackage api.objects
 */
class BorhanPlayReadyCopyEnablerHolderArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanPlayReadyCopyEnablerHolderArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $type)
		{
    		$nObj = new BorhanPlayReadyCopyEnablerHolder();
			$nObj->type = $type;
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanPlayReadyCopyEnablerHolder");	
	}
}
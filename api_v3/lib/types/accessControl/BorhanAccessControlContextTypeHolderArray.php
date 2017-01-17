<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanContextTypeHolderArray
 */
class BorhanAccessControlContextTypeHolderArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanAccessControlContextTypeHolderArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $type)
		{
    		$nObj = new BorhanAccessControlContextTypeHolder();
			$nObj->type = $type;
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanAccessControlContextTypeHolder");	
	}
}
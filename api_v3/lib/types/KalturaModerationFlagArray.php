<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanModerationFlagArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanModerationFlagArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanModerationFlag();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanModerationFlag");
	}
}

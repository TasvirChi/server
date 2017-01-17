<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanDetachedResponseProfileArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDetachedResponseProfileArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanDetachedResponseProfile();
			if(!$nObj)
			{
				BorhanLog::alert("Object [" . get_class($obj) . "] type [" . $obj->getType() . "] could not be translated to API object");
				continue;
			}
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}

	public function __construct()
	{
		parent::__construct("BorhanDetachedResponseProfile");	
	}
}
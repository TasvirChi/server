<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFlavorParamsArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanFlavorParamsArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = BorhanFlavorParamsFactory::getFlavorParamsInstance($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanFlavorParams");	
	}
}
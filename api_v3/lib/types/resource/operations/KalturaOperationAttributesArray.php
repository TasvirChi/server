<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanOperationAttributesArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanOperationAttributesArray();
		if(is_null($arr))
			return $newArr;
			
		foreach($arr as $obj)
		{
			$class = $obj->getApiType();
			$nObj = new $class();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanOperationAttributes");	
	}
}
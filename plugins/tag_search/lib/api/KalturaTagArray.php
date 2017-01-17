<?php
/**
 * @package plugins.tagSearch
 * @subpackage api.objects
 */
class BorhanTagArray extends BorhanTypedArray
{
    /**
     * Function returns an array of API objects for the array of DB 
     * objects it is passed.
     * @param array $arr
     * @return BorhanTagArray
     */
    public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanTagArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanTag();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanTag");	
	}
}
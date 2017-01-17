<?php
/**
 * @package plugins.annotation
 * @subpackage api.objects
 */
class BorhanAnnotationArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanAnnotationArray();
		if ($arr == null)
			return $newArr;
		
		foreach ($arr as $obj)
		{
    		$nObj = new BorhanAnnotation();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanAnnotation");	
	}
}

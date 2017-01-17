<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanReportArray extends BorhanTypedArray
{
	public function __construct()
	{
		return parent::__construct("BorhanReport");
	}
	
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanReportArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanReport();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
}
?>
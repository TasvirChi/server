<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.objects
 */
class BorhanBusinessProcessCaseArray extends BorhanTypedArray
{
	public static function fromDbArray($arr)
	{
		$newArr = new BorhanBusinessProcessCaseArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			/* @var $obj kBusinessProcessCase */
    		$nObj = new BorhanBusinessProcessCase();
			$nObj->fromObject($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanBusinessProcessCase");	
	}
}
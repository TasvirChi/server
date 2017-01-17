<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanGroupUserArray extends BorhanTypedArray
{
	public static function fromDbArray($arr)
	{
		$newArr = new BorhanGroupUserArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanGroupUser();
			$nObj->fromObject($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanGroupUser");
	}
}
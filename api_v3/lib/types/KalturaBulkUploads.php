<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBulkUploads extends BorhanTypedArray
{
	public static function fromBatchJobArray ($arr)
	{
		$newArr = new BorhanBulkUploads();
		if ($arr == null)
			return $newArr;
					
		foreach ($arr as $obj)
		{
			$nObj = new BorhanBulkUpload();
			$nObj->fromObject($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanBulkUpload");	
	}
}
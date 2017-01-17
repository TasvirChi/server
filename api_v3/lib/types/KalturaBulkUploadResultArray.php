<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBulkUploadResultArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanBulkUploadResultArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanBulkUploadResult();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanBulkUploadResult" );
	}
}

<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUserLoginDataArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanUserLoginDataArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanUserLoginData();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanUserLoginData" );
	}
}

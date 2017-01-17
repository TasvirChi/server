<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUserArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanUserArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanUser();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanUser" );
	}
}

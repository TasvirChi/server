<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUiConfArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanUiConfArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanUiConf();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanUiConf" );
	}
}

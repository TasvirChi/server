<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanWidgetArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanWidgetArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanWidget();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanWidget" );
	}
}

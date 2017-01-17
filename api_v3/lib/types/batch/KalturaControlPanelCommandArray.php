<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanControlPanelCommandArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanControlPanelCommandArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanControlPanelCommand();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanControlPanelCommand" );
	}
}

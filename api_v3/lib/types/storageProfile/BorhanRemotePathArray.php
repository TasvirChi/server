<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanRemotePathArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanRemotePathArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanRemotePath();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanRemotePath" );
	}
}

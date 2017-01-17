<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanSchedulerConfigArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanSchedulerConfigArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanSchedulerConfig();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanSchedulerConfig" );
	}
}

<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanSchedulerArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanSchedulerArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanScheduler();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public static function statusFromSchedulerArray( $arr )
	{
		$newArr = new BorhanSchedulerArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanScheduler();
			$nObj->statusFromObject($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanScheduler" );
	}
}

<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanSchedulerWorkerArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanSchedulerWorkerArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanSchedulerWorker();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public static function statusFromSchedulerWorkerArray( $arr )
	{
		$newArr = new BorhanSchedulerWorkerArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanSchedulerWorker();
			$nObj->statusFromObject($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanSchedulerWorker" );
	}
}

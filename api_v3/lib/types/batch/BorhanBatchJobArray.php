<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBatchJobArray extends BorhanTypedArray
{
	public static function fromStatisticsBatchJobArray ( $arr )
	{
		$newArr = new BorhanBatchJobArray();
		if ( is_array ( $arr ) )
		{
			foreach ( $arr as $obj )
			{
				$nObj = new BorhanBatchJob();
				$nObj->fromStatisticsObject($obj);
				$newArr[] = $nObj;
			}
		}
		
		return $newArr;
	}
	
	public static function fromBatchJobArray ( $arr )
	{
		$newArr = new BorhanBatchJobArray();
		if ( is_array ( $arr ) )
		{
			foreach ( $arr as $obj )
			{
				$nObj = new BorhanBatchJob();
				$nObj->fromBatchJob($obj);
				$newArr[] = $nObj;
			}
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanBatchJob" );
	}
}
?>
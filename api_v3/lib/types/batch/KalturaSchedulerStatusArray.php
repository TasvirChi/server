<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanSchedulerStatusArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanSchedulerStatusArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanSchedulerStatus();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public static function fromValuesArray($arr, $schedulerId, $schedulerConfiguredId, $workerId = null, $workerConfiguredId = null, $workerType = null)
	{
		$newArr = new BorhanSchedulerStatusArray();
		foreach ( $arr as $type => $value)
		{
			$status = new BorhanSchedulerStatus();
			$status->type = $type;
			$status->value = $value;
			
			$status->schedulerId = $schedulerId;
			$status->schedulerConfiguredId = $schedulerConfiguredId;
			
			$status->workerId = $workerId;
			$status->workerConfiguredId = $workerConfiguredId;
			$status->workerType = $workerType;
			
			$newArr[] = $status;
		}
		
		return $newArr;
	}
	
	public function toValuesArray( )
	{
		$ret = array();
		for($i = 0; $i < $this->count; $i++)
		{
			$status = $this->offsetGet[$i];
			$ret[$status->type] = $status->value;
		}
		return $ret;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanSchedulerStatus" );
	}
}

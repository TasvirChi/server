<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFeatureStatusArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanFeatureStatusArray();
		foreach($arr as $obj)
		{
			if ($obj){
				$nObj = new BorhanFeatureStatus();
				$nObj->fromObject($obj, $responseProfile);
				$newArr[] = $nObj;
			}
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanFeatureStatus" );
	}
}
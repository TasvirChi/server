<?php
/**
 * @package plugins.freewheelDistribution
 * @subpackage api.objects
 */
class BorhanFreewheelDistributionAssetPathArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanFreewheelDistributionAssetPathArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanFreewheelDistributionAssetPath();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanFreewheelDistributionAssetPath");	
	}
}
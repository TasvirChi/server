<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanGenericDistributionProviderActionArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanGenericDistributionProviderActionArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanGenericDistributionProviderAction();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanGenericDistributionProviderAction");	
	}
}
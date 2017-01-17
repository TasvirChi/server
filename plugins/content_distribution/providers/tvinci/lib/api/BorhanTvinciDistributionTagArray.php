<?php
/**
 * @package plugins.tvinciDistribution
 * @subpackage api.objects
 */
class BorhanTvinciDistributionTagArray extends BorhanTypedArray
{
	public function __construct()
	{
		parent::__construct("BorhanTvinciDistributionTag");
	}
	
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanTvinciDistributionTagArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanTvinciDistributionTag();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}

		return $newArr;
	}
}
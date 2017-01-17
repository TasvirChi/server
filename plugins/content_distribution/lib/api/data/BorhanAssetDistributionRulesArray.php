<?php

/**
 * Array of asset distribution rules
 *
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanAssetDistributionRulesArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanAssetDistributionRulesArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanAssetDistributionRule();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}

		return $newArr;
	}

	public function __construct()
	{
		parent::__construct("BorhanAssetDistributionRule");
	}
}
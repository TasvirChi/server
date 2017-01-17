<?php

/**
 * Array of asset distribution conditions
 *
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanAssetDistributionConditionsArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanAssetDistributionConditionsArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			switch(get_class($obj))
			{
				case 'kAssetDistributionPropertyCondition':
					$nObj = new BorhanAssetDistributionPropertyCondition();
					break;
			}

			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}

		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanAssetDistributionCondition");
	}
}
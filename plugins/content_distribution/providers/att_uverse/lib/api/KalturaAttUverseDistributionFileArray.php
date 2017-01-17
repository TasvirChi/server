<?php
/**
 * @package plugins.attUverseDistribution
 * @subpackage api.objects
 */
class BorhanAttUverseDistributionFileArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanAttUverseDistributionFileArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanAttUverseDistributionFile();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}

		return $newArr;
	}

	public function __construct()
	{
		parent::__construct("BorhanAttUverseDistributionFile");	
	}
}
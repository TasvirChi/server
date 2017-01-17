<?php
/**
 * @package plugins.facebookDistribution
 * @subpackage api.objects
 */
class BorhanFacebookCaptionDistributionInfoArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanFacebookCaptionDistributionInfoArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanFacebookCaptionDistributionInfo();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanFacebookCaptionDistributionInfo");
	}
}
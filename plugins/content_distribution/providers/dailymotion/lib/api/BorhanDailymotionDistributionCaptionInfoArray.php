<?php
/**
 * @package plugins.dailymotionDistribution
 * @subpackage api.objects
 */
class BorhanDailymotionDistributionCaptionInfoArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDailymotionDistributionCaptionInfoArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanDailymotionDistributionCaptionInfo();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanDailymotionDistributionCaptionInfo");	
	}
}
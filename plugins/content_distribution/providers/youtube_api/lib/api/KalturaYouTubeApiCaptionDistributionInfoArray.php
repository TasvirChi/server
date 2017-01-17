<?php
/**
 * @package plugins.youtubeApiDistribution
 * @subpackage api.objects
 */
class BorhanYouTubeApiCaptionDistributionInfoArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanYouTubeApiCaptionDistributionInfoArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = new BorhanYouTubeApiCaptionDistributionInfo();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanYouTubeApiCaptionDistributionInfo");	
	}
}
<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanDistributionProfileArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDistributionProfileArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = BorhanDistributionProfileFactory::createBorhanDistributionProfile($obj->getProviderType());
    		if(!$nObj)
    		{
    			BorhanLog::err("Distribution Profile Factory could not find matching profile type for provider [" . $obj->getProviderType() . "]");
    			continue;
    		}
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanDistributionProfile");	
	}
}
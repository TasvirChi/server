<?php
/**
 * @package plugins.partnerAggregation
 * @subpackage api.objects
 */
class BorhanDwhHourlyPartnerArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDwhHourlyPartnerArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanDwhHourlyPartner();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanDwhHourlyPartner");	
	}
}
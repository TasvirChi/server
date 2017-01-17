<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanConversionProfileArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanConversionProfileArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanConversionProfile();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanConversionProfile");	
	}
	
	public function loadFlavorParamsIds()
	{
		$conversionProfileIds = array();
		
		// find all profile ids
		foreach($this as $conversionProfile)
		{
			$conversionProfileIds[] = $conversionProfile->id;
		}
		// get all params relations by the profile ids list
		$c = new Criteria();
		$c->add(flavorParamsConversionProfilePeer::CONVERSION_PROFILE_ID, $conversionProfileIds, Criteria::IN);
		$allParams = flavorParamsConversionProfilePeer::doSelect($c);
		$paramsIdsPerProfile = array();
		
		// group the params by profile id
		foreach($allParams as $item)
		{
			if (!isset($paramsIdsPerProfile[$item->getConversionProfileId()]))
				$paramsIdsPerProfile[$item->getConversionProfileId()] = array();
			$paramsIdsPerProfile[$item->getConversionProfileId()][] = $item->getFlavorParamsId();
		}
		
		// assign the params ids to the profiles
		foreach($this as $conversionProfile)
		{
			if (isset($paramsIdsPerProfile[$conversionProfile->id]))
				$conversionProfile->flavorParamsIds =  implode(",", $paramsIdsPerProfile[$conversionProfile->id]);
		}
	}
}
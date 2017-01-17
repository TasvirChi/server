<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBaseSyndicationFeedArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanBaseSyndicationFeedArray();
		if ( $arr == null ) return $newArr;
		foreach ( $arr as $obj )
		{
			$nObj = BorhanSyndicationFeedFactory::getInstanceByType($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanBaseSyndicationFeed");	
	}
}
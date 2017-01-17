<?php
/**
 * @package plugins.drm
 * @subpackage api.objects
 */
class BorhanDrmPolicyArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDrmPolicyArray();
		foreach ( $arr as $obj )
		{
		    $nObj = BorhanDrmPolicy::getInstanceByType($obj->getProvider());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( 'BorhanDrmPolicy' );
	}
}

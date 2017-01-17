<?php
/**
 * @package plugins.drm
 * @subpackage api.objects
 */
class BorhanDrmProfileArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDrmProfileArray();
		foreach ( $arr as $obj )
		{
		    $nObj = BorhanDrmProfile::getInstanceByType($obj->getProvider());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( 'BorhanDrmProfile' );
	}
}

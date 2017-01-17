<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanStorageProfileArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanStorageProfileArray();
		foreach($arr as $obj)
		{
		    /* @var $obj StorageProfile */
			$nObj = BorhanStorageProfile::getInstanceByType($obj->getProtocol());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanStorageProfile" );
	}
}

<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.objects
 */
class BorhanDropFolderArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDropFolderArray();
		foreach ( $arr as $obj )
		{
		    $nObj = BorhanDropFolder::getInstanceByType($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( 'BorhanDropFolder' );
	}
}

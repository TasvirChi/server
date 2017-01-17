<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.objects
 */
class BorhanDropFolderFileArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDropFolderFileArray();
		foreach ( $arr as $obj )
		{
			$nObj = BorhanDropFolderFile::getInstanceByType($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( 'BorhanDropFolderFile' );
	}
}

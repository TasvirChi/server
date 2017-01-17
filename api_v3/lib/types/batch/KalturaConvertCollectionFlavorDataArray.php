<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanConvertCollectionFlavorDataArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanConvertCollectionFlavorDataArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanConvertCollectionFlavorData();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanConvertCollectionFlavorData" );
	}
}

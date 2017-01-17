<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated
 */
class BorhanSearchResultArray extends BorhanTypedArray
{
	public static function fromSearchResultArray ( $arr , BorhanSearch $search )
	{
		$newArr = new BorhanSearchResultArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanSearchResult();
			$nObj->fromSearchResult( $obj , $search );
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanSearchResult" );
	}
}
?>
<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanMediaEntryFilterForPlaylistArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanMediaEntryFilterForPlaylist();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanMediaEntryFilterForPlaylist" );
	}
}

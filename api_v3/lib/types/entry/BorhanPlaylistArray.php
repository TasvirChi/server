<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPlaylistArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanPlaylistArray();
		if ( $arr == null ) return $newArr;
		foreach ( $arr as $obj )
		{
    		$nObj = BorhanEntryFactory::getInstanceByType($obj->getType());
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanPlaylist");	
	}
}
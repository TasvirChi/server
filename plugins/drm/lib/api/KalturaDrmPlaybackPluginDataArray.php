<?php
/**
 * @package plugins.drm
 * @subpackage api.objects
 */
class BorhanDrmPlaybackPluginDataArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDrmPlaybackPluginDataArray();
		foreach ( $arr as $obj )
		{
			$nObj = BorhanPluginManager::loadObject('BorhanDrmPlaybackPluginData', get_class($obj));
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
		 
	}
	
	public function __construct( )
	{
		return parent::__construct ( 'BorhanDrmPlaybackPluginData' );
	}
}

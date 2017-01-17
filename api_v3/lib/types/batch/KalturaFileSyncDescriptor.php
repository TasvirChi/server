<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFileSyncDescriptor extends BorhanObject
{
	/**
	 * @var string
	 */
	public $fileSyncLocalPath;
	
	/**
	 * The translated path as used by the scheduler
	 * @var string
	 */
	public $fileSyncRemoteUrl;
	
	/**
	 * @var int
	 */
	public $fileSyncObjectSubType;
	
	private static $map_between_objects = array
	(
		"fileSyncLocalPath" ,
		"fileSyncRemoteUrl" ,
		"fileSyncObjectSubType" ,
	);


	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kFileSyncDescriptor();
			
		return parent::toObject($dbObject, $skip);
	}
}
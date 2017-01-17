<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanConvartableJobData extends BorhanJobData
{
	/**
	 * @var string
	 * @deprecated
	 */
	public $srcFileSyncLocalPath;
	
	/**
	 * The translated path as used by the scheduler
	 * @var string
	 * @deprecated
	 */
	public $actualSrcFileSyncLocalPath;
	
	/**
	 * @var string
	 * @deprecated
	 */
	public $srcFileSyncRemoteUrl;

	/**
	 * 
	 * @var BorhanSourceFileSyncDescriptorArray
	 */
	public $srcFileSyncs;
	
	/**
	 * @var int
	 */
	public $engineVersion;
	
	/**
	 * @var int
	 */
	public $flavorParamsOutputId;
	
	/**
	 * @var BorhanFlavorParamsOutput
	 */
	public $flavorParamsOutput;
	
	/**
	 * @var int
	 */
	public $mediaInfoId;
	
	/**
	 * @var int
	 */
	public $currentOperationSet;
	
	/**
	 * @var int
	 */
	public $currentOperationIndex;
	
	/**
	 * @var BorhanKeyValueArray
	 */
	public $pluginData;
	
	private static $map_between_objects = array
	(
		"srcFileSyncs",
		"engineVersion" ,
		"mediaInfoId" ,
		"flavorParamsOutputId" ,
		"currentOperationSet" ,
		"currentOperationIndex" ,
		"pluginData",
	);


	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	    
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject(  $dbConvartableJobData = null, $props_to_skip = array()) 
	{
		if(is_null($dbConvartableJobData))
			$dbConvartableJobData = new kConvartableJobData();
			
		return parent::toObject($dbConvartableJobData, $props_to_skip);
	}
	    
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($srcObj)
	 */
	public function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null) 
	{
		/* @var $srcObj kConvartableJobData */
		$srcObj->migrateOldSerializedData();
		parent::doFromObject($srcObj, $responseProfile);
	}
}

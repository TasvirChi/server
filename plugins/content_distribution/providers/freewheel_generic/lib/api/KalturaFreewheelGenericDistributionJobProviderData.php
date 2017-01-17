<?php
/**
 * @package plugins.freewheelGenericDistribution
 * @subpackage api.objects
 */
class BorhanFreewheelGenericDistributionJobProviderData extends BorhanConfigurableDistributionJobProviderData
{
	/**
	 * Demonstrate passing array of paths to the job
	 * 
	 * @var BorhanStringArray
	 */
	public $videoAssetFilePaths;
	
	/**
	 * Demonstrate passing single path to the job
	 * 
	 * @var string
	 */
	public $thumbAssetFilePath;
	
	/**
	 * @var BorhanCuePointArray
	 */
	public $cuePoints;
	

	/**
	 * Called on the server side and enables you to populate the object with any data from the DB
	 * 
	 * @param BorhanDistributionJobData $distributionJobData
	 */
	public function __construct(BorhanDistributionJobData $distributionJobData = null)
	{
		parent::__construct($distributionJobData);
		
		if(!$distributionJobData)
			return;
			
		if(!($distributionJobData->distributionProfile instanceof BorhanFreewheelGenericDistributionProfile))
			return;
			
		$this->videoAssetFilePaths = new BorhanStringArray();
		
		// loads all the flavor assets that should be submitted to the remote destination site
		$flavorAssets = assetPeer::retrieveByIds(explode(',', $distributionJobData->entryDistribution->flavorAssetIds));
		foreach($flavorAssets as $flavorAsset)
		{
			$videoAssetFilePath = new BorhanString();
			$syncKey = $flavorAsset->getSyncKey(flavorAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
			$videoAssetFilePath->value = kFileSyncUtils::getLocalFilePathForKey($syncKey, false);
			$this->videoAssetFilePaths[] = $videoAssetFilePath;
		}
		
		$thumbAssets = assetPeer::retrieveByIds(explode(',', $distributionJobData->entryDistribution->thumbAssetIds));
		if(count($thumbAssets))
		{
			$thumbAsset = reset($thumbAssets);
			$syncKey = $thumbAssets->getSyncKey(thumbAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
			$this->thumbAssetFilePath = kFileSyncUtils::getLocalFilePathForKey($syncKey, false);
		}
		
		// entry cue points
		$c = BorhanCriteria::create(CuePointPeer::OM_CLASS);
		$c->add(CuePointPeer::PARTNER_ID, $distributionJobData->entryDistribution->partnerId);
		$c->add(CuePointPeer::ENTRY_ID, $distributionJobData->entryDistribution->entryId);
		$c->add(CuePointPeer::TYPE, AdCuePointPlugin::getCuePointTypeCoreValue(AdCuePointType::AD));
		$c->addAscendingOrderByColumn(CuePointPeer::START_TIME);
		$cuePointsDb = CuePointPeer::doSelect($c);
		$this->cuePoints = BorhanCuePointArray::fromDbArray($cuePointsDb);
	}
		
	/**
	 * Maps the object attributes to getters and setters for Core-to-API translation and back
	 *  
	 * @var array
	 */
	private static $map_between_objects = array
	(
		"thumbAssetFilePath",
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($object = null, $skip = array())
	{
		$object = parent::toObject($object, $skip);
		
		if($this->videoAssetFilePaths)
		{
			$videoAssetFilePaths = array();
			foreach($this->videoAssetFilePaths as $videoAssetFilePath)
			{
				/* @var $videoAssetFilePath BorhanString */
				$videoAssetFilePaths[] = $videoAssetFilePath->value;
			}
				
			$object->setVideoAssetFilePaths($videoAssetFilePaths);
		}
		
		return $object;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($object, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($object, $responseProfile);
		$videoAssetFilePaths = $object->getVideoAssetFilePaths();
		if($videoAssetFilePaths && is_array($videoAssetFilePaths))
		{
			$this->videoAssetFilePaths = new BorhanStringArray();
			foreach($videoAssetFilePaths as $assetFilePath)
			{
				$videoAssetFilePath = new BorhanString();
				$videoAssetFilePath->value = $assetFilePath;
				$this->videoAssetFilePaths[] = $videoAssetFilePath;
			}
		}
	}
}

<?php
/**
 * @package plugins.quickPlayDistribution
 * @subpackage api.objects
 */
class BorhanQuickPlayDistributionJobProviderData extends BorhanConfigurableDistributionJobProviderData
{
	/**
	 * @var string
	 */
	public $xml;
	
	/**
	 * @var BorhanStringArray
	 */
	public $videoFilePaths;

	/**
	 * @var BorhanStringArray
	 */
	public $thumbnailFilePaths;

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
			
		if(!($distributionJobData->distributionProfile instanceof BorhanQuickPlayDistributionProfile))
			return;
			
		$this->videoFilePaths = new BorhanStringArray();
		$this->thumbnailFilePaths = new BorhanStringArray();

		// loads all the flavor assets that should be submitted to the remote destination site
		$flavorAssets = assetPeer::retrieveByIds(explode(',', $distributionJobData->entryDistribution->flavorAssetIds));
		$thumbAssets = assetPeer::retrieveByIds(explode(',', $distributionJobData->entryDistribution->thumbAssetIds));
		$entry = entryPeer::retrieveByPK($distributionJobData->entryDistribution->entryId);
		
		foreach($flavorAssets as $asset)
		{
			$syncKey = $asset->getSyncKey(flavorAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
			if(kFileSyncUtils::fileSync_exists($syncKey))
			{
				$str = new BorhanString();
				$str->value = kFileSyncUtils::getLocalFilePathForKey($syncKey, false);
			    $this->videoFilePaths[] = $str;
			}
		}
		
		foreach($thumbAssets as $asset)
		{
			$syncKey = $asset->getSyncKey(thumbAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
			if(kFileSyncUtils::fileSync_exists($syncKey))
			{
				$str = new BorhanString();
				$str->value = kFileSyncUtils::getLocalFilePathForKey($syncKey, false);
			    $this->thumbnailFilePaths[] = $str;
			}
		}
		
		$feed = new QuickPlayFeed($distributionJobData, $this, $flavorAssets, $thumbAssets, $entry);
		$this->xml = $feed->getXml();
	}
		
	/**
	 * Maps the object attributes to getters and setters for Core-to-API translation and back
	 *  
	 * @var array
	 */
	private static $map_between_objects = array
	(
		'xml',
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
}

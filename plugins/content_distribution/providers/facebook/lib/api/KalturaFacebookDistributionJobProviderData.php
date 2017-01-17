<?php
/**
 * @package plugins.facebookDistribution
 * @subpackage api.objects
 */
class BorhanFacebookDistributionJobProviderData extends BorhanConfigurableDistributionJobProviderData
{
	/**
	 * @var string
	 */
	public $videoAssetFilePath;
	
	/**
	 * @var string
	 */
	public $thumbAssetFilePath;

	/**
	 * @var BorhanFacebookCaptionDistributionInfoArray
	 */
	public $captionsInfo;

	public function __construct(BorhanDistributionJobData $distributionJobData = null)
	{
		parent::__construct($distributionJobData);
	    
		if( (!$distributionJobData) ||
			!($distributionJobData->distributionProfile instanceof BorhanFacebookDistributionProfile) ){
			BorhanLog::info("Distribution data given did not exist or was not facebook related, given: ".print_r($distributionJobData, true));
			return;
		}

		$this->videoAssetFilePath = $this->getValidVideoPath($distributionJobData);

		if(!$this->videoAssetFilePath){
			BorhanLog::err("Could not find a valid video asset");
			return;
		}


		$thumbAssets = assetPeer::retrieveByIds(explode(',', $distributionJobData->entryDistribution->thumbAssetIds));
		if(count($thumbAssets))
		{
			$syncKey = reset($thumbAssets)->getSyncKey(thumbAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
			if(kFileSyncUtils::fileSync_exists($syncKey))
				$this->thumbAssetFilePath = kFileSyncUtils::getLocalFilePathForKey($syncKey, false);
		}

		$this->addCaptionsData($distributionJobData);
	}
	
	private static $map_between_objects = array
	(
		"videoAssetFilePath",
		"thumbAssetFilePath",
		"captionsInfo"
	);

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	private function addCaptionsData(BorhanDistributionJobData $distributionJobData) 
	{
		$assetIdsArray = explode ( ',', $distributionJobData->entryDistribution->assetIds );
		if (empty($distributionJobData->entryDistribution->assetIds) || empty($assetIdsArray)) return;
		$this->captionsInfo = new BorhanFacebookCaptionDistributionInfoArray();
		
		foreach ( $assetIdsArray as $assetId ) 
		{
			$asset = assetPeer::retrieveByIdNoFilter( $assetId );
			if (!$asset)
			{
				BorhanLog::err("Asset [$assetId] not found");
				continue;
			}
			if($asset->getType() != CaptionPlugin::getAssetTypeCoreValue ( CaptionAssetType::CAPTION ))
			{
				BorhanLog::debug("Asset [$assetId] is not a caption");
				continue;				
			}
			if ($asset->getStatus() == asset::ASSET_STATUS_READY) 
			{
				$syncKey = $asset->getSyncKey ( asset::FILE_SYNC_ASSET_SUB_TYPE_ASSET );
				if (kFileSyncUtils::fileSync_exists ( $syncKey )) 
				{
					$captionInfo = $this->getCaptionInfo($asset);
					if($captionInfo)
					{
						$captionInfo->filePath = kFileSyncUtils::getLocalFilePathForKey ( $syncKey, false );
						$this->captionsInfo [] = $captionInfo;
					}					 
				}						
			}
			else
			{
				BorhanLog::debug("Asset [$assetId] has status [".$asset->getStatus()."]. not added to provider data");
			}
		}
	}
	
	private function getCaptionInfo($asset)
	{
		$captionInfo = new BorhanFacebookCaptionDistributionInfo();
		$captionInfo->assetId = $asset->getId();
		$captionInfo->version = $asset->getVersion();
		$captionInfo->label = $asset->getLabel();
		$captionInfo->language = $asset->getLanguage();
		
		if(!$captionInfo->label && !$captionInfo->language)
		{
			BorhanLog::err('The caption ['.$asset->getId().'] has unrecognized language ['.$asset->getLanguage().'] and label ['.$asset->getLabel().']');
			return null;
		}

		return $captionInfo;
	}
	
	private function getValidVideoPath(BorhanDistributionJobData $distributionJobData)
	{
		$flavorAssets = array();
		$videoAssetFilePath = null;
		$isValidVideo = false;
		
		if(count($distributionJobData->entryDistribution->flavorAssetIds))
		{
			$flavorAssets = assetPeer::retrieveByIds(explode(',', $distributionJobData->entryDistribution->flavorAssetIds));
		}
		else 
		{
			$flavorAssets = assetPeer::retrieveReadyFlavorsByEntryId($distributionJobData->entryDistribution->entryId);
		}
		
		foreach ($flavorAssets as $flavorAsset) 
		{
			$syncKey = $flavorAsset->getSyncKey(flavorAsset::FILE_SYNC_ASSET_SUB_TYPE_ASSET);
			if(kFileSyncUtils::fileSync_exists($syncKey))
			{
				$videoAssetFilePath = kFileSyncUtils::getLocalFilePathForKey($syncKey, false);
				$mediaInfo = mediaInfoPeer::retrieveByFlavorAssetId($flavorAsset->getId());
				if($mediaInfo)
				{
					try
					{
						FacebookGraphSdkUtils::validateVideoAttributes($videoAssetFilePath, $mediaInfo->getFileSize(), $mediaInfo->getVideoDuration());
						$isValidVideo = true;
					}
					catch(Exception $e)
					{
						BorhanLog::debug('Asset ['.$flavorAsset->getId().'] not valid for distribution: '.$e->getMessage());
					}	
				}
				if($isValidVideo)
					break;		
			}				
		}		
		return $videoAssetFilePath;
	}

}

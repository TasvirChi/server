<?php
/**
 * @package plugins.crossBorhanDistribution
 * @subpackage api.objects
 */
class BorhanCrossBorhanDistributionJobProviderData extends BorhanConfigurableDistributionJobProviderData
{
    /**
     * Key-value array where the keys are IDs of distributed flavor assets in the source account and the values are the matching IDs in the target account
     * @var string
     */
    public $distributedFlavorAssets;
    
    /**
     * Key-value array where the keys are IDs of distributed thumb assets in the source account and the values are the matching IDs in the target account
     * @var string
     */
    public $distributedThumbAssets;
    
    /**
     * Key-value array where the keys are IDs of distributed metadata objects in the source account and the values are the matching IDs in the target account
     * @var string
     */
    public $distributedMetadata;
    
    /**
     * Key-value array where the keys are IDs of distributed caption assets in the source account and the values are the matching IDs in the target account
     * @var string
     */
    public $distributedCaptionAssets;
    
    /**
     * Key-value array where the keys are IDs of distributed cue points in the source account and the values are the matching IDs in the target account
     * @var string
     */
    public $distributedCuePoints;
    
    
    
    public function __construct(BorhanDistributionJobData $distributionJobData = null)
	{			   
		parent::__construct($distributionJobData);
	    
		if (!$distributionJobData) {
			return;
		}
			
		if (!($distributionJobData->distributionProfile instanceof BorhanCrossBorhanDistributionProfile)) {
			return;
		}
					
		// load previously distributed data from entry distribution	
		$entryDistributionDb = EntryDistributionPeer::retrieveByPK($distributionJobData->entryDistributionId);
		if (!$entryDistributionDb)
		{
		    BorhanLog::err('Entry distribution ['.$distributionJobData->entryDistributionId.'] not found');
		    return;
		}
		
		$this->distributedFlavorAssets = $entryDistributionDb->getFromCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_FLAVOR_ASSETS);
		$this->distributedThumbAssets = $entryDistributionDb->getFromCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_THUMB_ASSETS);
		$this->distributedMetadata = $entryDistributionDb->getFromCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_METADATA);
		$this->distributedCaptionAssets = $entryDistributionDb->getFromCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_CAPTION_ASSETS);
		$this->distributedCuePoints = $entryDistributionDb->getFromCustomData(CrossBorhanDistributionCustomDataField::DISTRIBUTED_CUE_POINTS);
	}
	
	
    private static $map_between_objects = array
	(
		'distributedFlavorAssets',
		'distributedThumbAssets',
		'distributedMetadata',
		'distributedCaptionAssets',
    	'distributedCuePoints',
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kCrossBorhanDistributionJobProviderData();
			
		return parent::toObject($dbObject, $skip);
	}
    
}

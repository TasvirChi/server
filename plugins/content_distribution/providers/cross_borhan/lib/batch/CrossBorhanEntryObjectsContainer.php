<?php
/**
 * @package plugins.crossBorhanDistribution
 * @subpackage lib.batch
 */
class CrossBorhanEntryObjectsContainer
{
    /**
     * @var BorhanBaseEntry
     */
    public $entry;
        
    /**
     * @var array<BorhanMetadata>
     */
    public $metadataObjects;
    
    /**
     * @var array<BorhanFlavorAsset>
     */
    public $flavorAssets;
    
    /**
     * @var array<BorhanContentResource>
     */
    public $flavorAssetsContent;
    
    /**
     * @var array<BorhanThumbAsset>
     */
    public $thumbAssets;
    
    /**
     * @var array<BorhanContentResource>
     */
    public $thumbAssetsContent;
    
    /**
     * @var array<BorhanCaptionAsset>
     */
    public $captionAssets;
    
    /**
     * @var array<BorhanContentResource>
     */
    public $captionAssetsContent;
    
    /**
     * @var array<BorhanCuePoint>
     */
    public $cuePoints;
    
    /**
     * Initialize all member variables
     */
    public function __construct()
    {
        $this->entry = null;
        $this->metadataObjects = array();
        $this->flavorAssets = array();
        $this->flavorAssetsContent = array();
        $this->thumbAssets = array();
        $this->thumbAssetsContent = array();
        $this->captionAssets = array();
        $this->captionAssetsContent = array();
        $this->cuePoints = array();
    }
}
<?php
/**
 * @package plugins.crossBorhanDistribution
 * @subpackage api.objects
 */
class BorhanCrossBorhanDistributionProfile extends BorhanConfigurableDistributionProfile
{
	/**
	 * @var string
	 */
	public $targetServiceUrl;
	
	/**
	 * @var int
	 */
	public $targetAccountId;
	
	/**
	 * @var string
	 */
	public $targetLoginId;
	
	/**
	 * @var string
	 */
	public $targetLoginPassword;
	
	/**
	 * @var string
	 */
	 public $metadataXslt;
	 
 	/**
	 * @var BorhanStringValueArray
	 */
	 public $metadataXpathsTriggerUpdate;
	 
	 /**
	  * @var bool
	  */
	 public $distributeCaptions;
	 
	 /**
	  * @var bool
	  */
	 public $distributeCuePoints;
	 
	 /**
	  * @var bool
	  */
	 public $distributeRemoteFlavorAssetContent;
	 
	 /**
	  * @var bool
	  */
	 public $distributeRemoteThumbAssetContent;
	 
	 /**
	  * @var bool
	  */
	 public $distributeRemoteCaptionAssetContent;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapAccessControlProfileIds;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapConversionProfileIds;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapMetadataProfileIds;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapStorageProfileIds;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapFlavorParamsIds;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapThumbParamsIds;
	 
	 /**
	  * @var BorhanKeyValueArray
	  */
	 public $mapCaptionParamsIds;
	 	  
	 
	 
	/*
	 * mapping between the field on this object (on the left) and the setter/getter on the object (on the right)  
	 */
	private static $map_between_objects = array 
	(
		'targetServiceUrl',
		'targetAccountId',
	    'targetLoginId',
		'targetLoginPassword',
		'metadataXslt',
	    'metadataXpathsTriggerUpdate' => 'additionalMetadataXpathsTriggerUpdate',
	    'distributeCaptions',
	    'distributeCuePoints',
	 	'distributeRemoteFlavorAssetContent',
	 	'distributeRemoteThumbAssetContent',
	 	'distributeRemoteCaptionAssetContent',
	);
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
    public function toObject ( $object_to_fill = null , $props_to_skip = array() )
	{
		if(is_null($object_to_fill))
			$object_to_fill = new CrossBorhanDistributionProfile();
		
		/* @var $object_to_fill CrossBorhanDistributionProfile */
		$object_to_fill =  parent::toObject($object_to_fill, $props_to_skip);
		
		if (!is_null($this->mapAccessControlProfileIds)) {
		    $object_to_fill->setMapAccessControlProfileIds($this->toKeyValueArray($this->mapAccessControlProfileIds));
		}
		if (!is_null($this->mapConversionProfileIds)) {
		    $object_to_fill->setMapConversionProfileIds($this->toKeyValueArray($this->mapConversionProfileIds));
	    }
		if (!is_null($this->mapMetadataProfileIds)) {
		    $object_to_fill->setMapMetadataProfileIds($this->toKeyValueArray($this->mapMetadataProfileIds));    
		}
		if (!is_null($this->mapStorageProfileIds)) {
		    $object_to_fill->setMapStorageProfileIds($this->toKeyValueArray($this->mapStorageProfileIds));
	    }
		if (!is_null($this->mapFlavorParamsIds)) {
		    $object_to_fill->setMapFlavorParamsIds($this->toKeyValueArray($this->mapFlavorParamsIds));
		}
		if (!is_null($this->mapThumbParamsIds)) {
		    $object_to_fill->setMapThumbParamsIds($this->toKeyValueArray($this->mapThumbParamsIds));
		}
		if (!is_null($this->mapCaptionParamsIds)) {
		    $object_to_fill->setMapCaptionParamsIds($this->toKeyValueArray($this->mapCaptionParamsIds));
		}
		
		return $object_to_fill;
	}
	
	public function doFromObject($source_object, BorhanDetachedResponseProfile $responseProfile = null)
	{
	    parent::doFromObject($source_object, $responseProfile);
	    
	    /* @var $source_object CrossBorhanDistributionProfile */
	    $this->mapAccessControlProfileIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapAccessControlProfileIds());
	    $this->mapConversionProfileIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapConversionProfileIds());
	    $this->mapMetadataProfileIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapMetadataProfileIds());
	    $this->mapStorageProfileIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapStorageProfileIds());
	    $this->mapFlavorParamsIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapFlavorParamsIds());
	    $this->mapThumbParamsIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapThumbParamsIds());
	    $this->mapCaptionParamsIds = BorhanKeyValueArray::fromKeyValueArray($source_object->getMapCaptionParamsIds());
	}
	
	
    protected function toKeyValueArray($apiKeyValueArray)
	{
	    $keyValueArray = array();
        if (count($apiKeyValueArray))
        {
            foreach($apiKeyValueArray as $keyValueObj)
            {
                /* @var $keyValueObj BorhanKeyValue */
			    $keyValueArray[$keyValueObj->key] = $keyValueObj->value;
            }
        }		
	    return $keyValueArray;
	}

}
<?php

/**
 * Represents the Bulk upload job data for filter bulk upload
 * @package plugins.bulkUploadFilter
 * @subpackage api.objects
 */
class BorhanBulkUploadFilterJobData extends BorhanBulkUploadJobData
{	
	/**
	 * Filter for extracting the objects list to upload 
	 * @var BorhanFilter
	 */
	public $filter;

	/**
	 * Template object for new object creation
	 * @var BorhanObject
	 */
	public $templateObject;
	
	/**
	 * 
	 * Maps between objects and the properties
	 * @var array
	 */
	private static $map_between_objects = array
	(
		"filter",
	);

	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	public function toObject($dbData = null, $props_to_skip = array()) 
	{
		if(is_null($dbData))
			$dbData = new kBulkUploadFilterJobData();
		
		switch (get_class($this->templateObject))
	    {
	        case 'BorhanCategoryEntry':
	           	$dbData->setTemplateObject(new categoryEntry());
	           	$this->templateObject->toObject($dbData->getTemplateObject());
	            break;
	        default:
	            break;
	    }
	    
		return parent::toObject($dbData);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($source_object, BorhanDetachedResponseProfile $responseProfile = null)
	{
	    parent::doFromObject($source_object, $responseProfile);
	    
	    /* @var $source_object kBulkUploadFilterJobData */
	    $this->filter = null;
	    switch (get_class($source_object->getFilter()))
	    {
	        case 'categoryEntryFilter':
	            $this->filter = new BorhanCategoryEntryFilter();
	            break;
	        case 'entryFilter':
	            $this->filter = new BorhanBaseEntryFilter();
	            break;
	        default:
	            break;
	    }
	    
	    if ($this->filter)
	    {
	        $this->filter->fromObject($source_object->getFilter());
	    }       
	    
	   	$this->templateObject = null;
	   	
	    switch (get_class($source_object->getTemplateObject()))
	    {
	        case 'categoryEntry':
	            $this->templateObject = new BorhanCategoryEntry();
	            break;
	        default:
	            break;
	    }
	    
	    if ($this->templateObject)
	    {
	        $this->templateObject->fromObject($source_object->getTemplateObject());
	    }       
	}
	
	public function toInsertableObject($object_to_fill = null , $props_to_skip = array())
	{
	    $dbObj = parent::toInsertableObject($object_to_fill, $props_to_skip);
	    
	    $this->setType();
	    
	    return $dbObj;
	}
	
	public function setType ()
	{
	    $this->type = kPluginableEnumsManager::coreToApi("BorhanBulkUploadType", BulkUploadFilterPlugin::getApiValue(BulkUploadFilterType::FILTER));
	}
}
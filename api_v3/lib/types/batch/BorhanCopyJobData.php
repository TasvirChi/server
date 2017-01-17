<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCopyJobData extends BorhanJobData
{
	/**
	 * The filter should return the list of objects that need to be copied.
	 * @var BorhanFilter
	 */
	public $filter;
	
	/**
	 * Indicates the last id that copied, used when the batch crached, to re-run from the last crash point.
	 * @var int
	 */
	public $lastCopyId;
	
	/**
	 * Template object to overwrite attributes on the copied object
	 * @var BorhanObject
	 */
	public $templateObject;
	
	private static $map_between_objects = array
	(
		"lastCopyId" ,
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
	public function toObject($dbData = null, $props_to_skip = array()) 
	{
		if(is_null($dbData))
			$dbData = new kCopyJobData();
			
		$dbData->setTemplateObject($this->templateObject->toObject());
		
		return parent::toObject($dbData, $props_to_skip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbData, BorhanDetachedResponseProfile $responseProfile = null) 
	{
		/* @var $dbData kCopyJobData */
		$filter = $dbData->getFilter();
		$filterType = get_class($filter);
		switch($filterType)
		{
			case 'entryFilter':
				$this->filter = new BorhanBaseEntryFilter();
				$this->templateObject = new BorhanBaseEntry();
				break;
				
			case 'categoryFilter':
				$this->filter = new BorhanCategoryFilter();
				$this->templateObject = new BorhanCategory();
				break;
				
			case 'categoryEntryFilter':
				$this->filter = new BorhanCategoryEntryFilter();
				$this->templateObject = new BorhanCategoryEntry();
				break;
				
			case 'categoryKuserFilter':
				$this->filter = new BorhanCategoryUserFilter();
				$this->templateObject = new BorhanCategoryUser();
				break;
				
			default:
				$this->filter = BorhanPluginManager::loadObject('BorhanFilter', $filterType);
		}
		if($this->filter)
			$this->filter->fromObject($filter);
		
		if($this->templateObject)
			$this->templateObject->fromObject($dbData->getTemplateObject());
		
		parent::doFromObject($dbData, $responseProfile);
	}
}

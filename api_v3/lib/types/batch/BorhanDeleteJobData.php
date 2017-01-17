<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanDeleteJobData extends BorhanJobData
{
	/**
	 * The filter should return the list of objects that need to be deleted.
	 * @var BorhanFilter
	 */
	public $filter;
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbData = null, $props_to_skip = array()) 
	{
		if(is_null($dbData))
			$dbData = new kDeleteJobData();
			
		return parent::toObject($dbData, $props_to_skip);
	}
	
	public function doFromObject($dbData, BorhanDetachedResponseProfile $responseProfile = null) 
	{
		/* @var $dbData kDeleteJobData */
		$filter = $dbData->getFilter();
		$filterType = get_class($filter);
		switch($filterType)
		{
			case 'categoryEntryFilter':
				$this->filter = new BorhanCategoryEntryFilter();
				break;
				
			case 'categoryKuserFilter':
				$this->filter = new BorhanCategoryUserFilter();
				break;

			case 'KuserKgroupFilter':
				$this->filter = new BorhanGroupUserFilter();
				break;
				
			case 'categoryFilter':
				$this->filter = new BorhanCategoryFilter();
 				break;
			
			default:
				$this->filter = BorhanPluginManager::loadObject('BorhanFilter', $filterType);
		}
		if($this->filter)
			$this->filter->fromObject($filter);
		
		parent::doFromObject($dbData, $responseProfile);
	}
}

<?php
/**
 * Job Data representing the provider of recipients for a single categoryId
 *
 * @package plugins.emailNotification
 * @subpackage model.data
 */
class BorhanEmailNotificationCategoryRecipientJobData extends BorhanEmailNotificationRecipientJobData
{
	/**
	 * @var BorhanCategoryUserFilter
	 */
	public $categoryUserFilter;
	
	private static $map_between_objects = array(
		'categoryUserFilter',
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanEmailNotificationRecipientJobData::setProviderType()
	 */
	protected function setProviderType() 
	{
		$this->providerType = BorhanEmailNotificationRecipientProviderType::CATEGORY;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($source_object, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($source_object, $responseProfile);
		$this->setProviderType();
		if ($source_object->getCategoryUserFilter())
		{
			$this->categoryUserFilter = new BorhanCategoryUserFilter();
			$this->categoryUserFilter->fromObject($source_object->getCategoryUserFilter());
		}
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kEmailNotificationCategoryRecipientJobData();
		
		return parent::toObject($dbObject, $propertiesToSkip);
	}
}
<?php
/**
 * JobData representing the dynamic user receipient array
 *
 * @package plugins.emailNotification
 * @subpackage model.data
 */
class BorhanEmailNotificationUserRecipientJobData extends BorhanEmailNotificationRecipientJobData
{
	/**
	 * @var BorhanUserFilter
	 */
	public $filter;
	
	private static $map_between_objects = array(
		'filter',
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
	protected function setProviderType() {
		$this->providerType = BorhanEmailNotificationRecipientProviderType::USER;	
		
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEmailNotificationStaticRecipientJobData */
		parent::doFromObject($dbObject, $responseProfile);
		$this->setProviderType();
		if ($dbObject->getFilter())
		{
			$this->filter = new BorhanUserFilter();
			$this->filter->fromObject($dbObject->getFilter());
		}
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kEmailNotificationUserRecipientJobData();
		
		return parent::toObject($dbObject, $propertiesToSkip);
	}
}
<?php
/**
 * API class for recipient provider which constructs a dynamic list of recipients according to a user filter
 *
 * @package plugins.emailNotification
 * @subpackage model.data
 */
class BorhanEmailNotificationUserRecipientProvider extends BorhanEmailNotificationRecipientProvider
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
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kEmailNotificationUserRecipientProvider();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}	
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($dbObject, $responseProfile);
		if ($dbObject->getFilter())
		{
			$this->filter = new BorhanUserFilter();
			$this->filter->fromObject($dbObject->getFilter());
		}
	}
}
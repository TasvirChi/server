<?php
/**
 * API object which provides the recipients of category related notifications.
 *
 * @package plugins.emailNotification
 * @subpackage model.data
 */
class BorhanEmailNotificationCategoryRecipientProvider extends BorhanEmailNotificationRecipientProvider
{
	/**
	 * The ID of the category whose subscribers should receive the email notification.
	 * @var BorhanStringValue
	 */
	public $categoryId;
	
	/**
	 * 
	 * @var BorhanCategoryUserProviderFilter
	 */
	public $categoryUserFilter;

	private static $map_between_objects = array(
		'categoryId',
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
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		$this->validate();
		if (is_null($dbObject))
			$dbObject = new kEmailNotificationCategoryRecipientProvider();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}	
	
	/**
	 * Validation function
	 * @throws BorhanEmailNotificationErrors::INVALID_FILTER_PROPERTY
	 */
	protected function validate ()
	{
		if ($this->categoryUserFilter)
		{
			if (isset ($this->categoryUserFilter->categoryIdEqual))
			{
				throw new BorhanAPIException(BorhanEmailNotificationErrors::INVALID_FILTER_PROPERTY, 'categoryIdEqual');
			}
		}
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($dbObject, $responseProfile);
		/* @var $dbObject kEmailNotificationCategoryRecipientProvider */
		$categoryIdFieldType = get_class($dbObject->getCategoryId());
		BorhanLog::info("Retrieving API object for categoryId fild of type [$categoryIdFieldType]");
		switch ($categoryIdFieldType)
		{
			case 'kObjectIdField':
				$this->categoryId = new BorhanObjectIdField();
				break;
			case 'kEvalStringField':
				$this->categoryId = new BorhanEvalStringField();
				break;
			case 'kStringValue':
				$this->categoryId = new BorhanStringValue();
				break;
			default:
				$this->categoryId = BorhanPluginManager::loadObject('BorhanStringValue', $categoryIdFieldType);
				break;
		}
		
		if ($this->categoryId)
		{
			$this->categoryId->fromObject($dbObject->getCategoryId());
		}
		
		if ($dbObject->getCategoryUserFilter())
		{
			$this->categoryUserFilter = new BorhanCategoryUserProviderFilter();
			$this->categoryUserFilter->fromObject($dbObject->getCategoryUserFilter());
		}

	}
} 
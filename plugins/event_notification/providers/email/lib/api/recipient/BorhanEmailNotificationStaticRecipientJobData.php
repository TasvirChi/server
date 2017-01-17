<?php
/**
 * JobData representing the static receipient array
 *
 * @package plugins.emailNotification
 * @subpackage model.data
 */
class BorhanEmailNotificationStaticRecipientJobData extends BorhanEmailNotificationRecipientJobData
{
	/**
	 * Email to emails and names
	 * @var BorhanKeyValueArray
	 */
	public $emailRecipients;
	
	private static $map_between_objects = array(
		'emailRecipients',
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
		$this->providerType = BorhanEmailNotificationRecipientProviderType::STATIC_LIST;	
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEmailNotificationStaticRecipientJobData */
		parent::doFromObject($dbObject, $responseProfile);
		$this->setProviderType();
		
		$this->emailRecipients = BorhanKeyValueArray::fromKeyValueArray($dbObject->getEmailRecipients());
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kEmailNotificationStaticRecipientJobData();
		
		return parent::toObject($dbObject, $propertiesToSkip);
	}
}
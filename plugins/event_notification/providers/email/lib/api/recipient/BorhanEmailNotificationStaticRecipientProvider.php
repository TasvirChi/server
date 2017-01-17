<?php
/**
 * API class for recipient provider containing a static list of email recipients.
 *
 * @package plugins.emailNotification
 * @subpackage model.data
 */
class BorhanEmailNotificationStaticRecipientProvider extends BorhanEmailNotificationRecipientProvider
{	
	/**
	 * Email to emails and names
	 * @var BorhanEmailNotificationRecipientArray
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
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kEmailNotificationStaticRecipientProvider();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}	
}
<?php

/**
 * @package plugins.scheduledTaskEventNotification
 * @subpackage api.objects.objectTasks
 */
class BorhanDispatchEventNotificationObjectTask extends BorhanObjectTask
{
	/**
	 * The event notification template id to dispatch
	 *
	 * @var int
	 */
	public $eventNotificationTemplateId;

	public function __construct()
	{
		$this->type = ScheduledTaskEventNotificationPlugin::getApiValue(DispatchEventNotificationObjectTaskType::DISPATCH_EVENT_NOTIFICATION);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUsage()
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		parent::validateForUsage($sourceObject, $propertiesToSkip);

		$this->validatePropertyNotNull('eventNotificationTemplateId');

		myPartnerUtils::addPartnerToCriteria('EventNotificationTemplate', kCurrentContext::getCurrentPartnerId(), true);
		$eventNotificationTemplate = EventNotificationTemplatePeer::retrieveByPK($this->eventNotificationTemplateId);
		if (is_null($eventNotificationTemplate))
			throw new BorhanAPIException(BorhanEventNotificationErrors::EVENT_NOTIFICATION_TEMPLATE_NOT_FOUND, $this->eventNotificationTemplateId);
	}

	public function toObject($dbObject = null, $skip = array())
	{
		/** @var kObjectTask $dbObject */
		$dbObject = parent::toObject($dbObject, $skip);
		$dbObject->setDataValue('eventNotificationTemplateId', $this->eventNotificationTemplateId);
		return $dbObject;
	}

	public function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($srcObj, $responseProfile);

		/** @var kObjectTask $srcObj */
		$this->eventNotificationTemplateId = $srcObj->getDataValue('eventNotificationTemplateId');
	}
}
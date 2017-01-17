<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventNotificationScope extends BorhanScope
{
	/**
	 * @var string
	 */
	public $objectId;

	/**
	 * @var BorhanEventNotificationEventObjectType
	 */
	public $scopeObjectType;

	public function toObject($objectToFill = null, $propsToSkip = array())
	{
		if (is_null($objectToFill))
			$objectToFill = new kEventNotificationScope();

		/** @var kEventNotificationScope $objectToFill */
		$objectToFill = parent::toObject($objectToFill);

		$objectClassName = BorhanPluginManager::getObjectClass('EventNotificationEventObjectType', kPluginableEnumsManager::apiToCore('EventNotificationEventObjectType', $this->scopeObjectType));
		$peerClass = $objectClassName.'Peer';
		$objectId = $this->objectId;
		if (class_exists($peerClass))
		{
			$objectToFill->setObject($peerClass::retrieveByPk($objectId));
		}
		else
		{
			$b = new $objectClassName();
			$peer = $b->getPeer();
			$object = $peer::retrieveByPK($objectId);
			$objectToFill->setObject($object);
		}

		if (is_null($objectToFill->getObject()))
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $this->objectId);

		return $objectToFill;
	}
}

<?php

/**
 * @package plugins.scheduledTaskEventNotification
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskDispatchEventNotificationEngine extends KObjectTaskEntryEngineBase
{
	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		/** @var BorhanDispatchEventNotificationObjectTask $objectTask */
		$objectTask = $this->getObjectTask();
		if (is_null($objectTask))
			return;

		$client = $this->getClient();
		$templateId = $objectTask->eventNotificationTemplateId;
		$eventNotificationPlugin = BorhanEventNotificationClientPlugin::get($client);
		$scope = new BorhanEventNotificationScope();
		$scope->objectId =$object->id;
		$scope->scopeObjectType = BorhanEventNotificationEventObjectType::ENTRY;
		$this->impersonate($object->partnerId);
		try
		{
			$eventNotificationPlugin->eventNotificationTemplate->dispatch($templateId, $scope);
			$this->unimpersonate();
		}
		catch(Exception $ex)
		{
			$this->unimpersonate();
			throw $ex;
		}

	}
}
<?php
/**
 * @package plugins.eventNotification
 * @subpackage Scheduler
 */
class KAsyncDispatchEventNotification extends KJobHandlerWorker
{
	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::EVENT_NOTIFICATION_HANDLER;
	}
	
	/* (non-PHPdoc)
	 * @see KJobHandlerWorker::exec()
	 */
	protected function exec(BorhanBatchJob $job)
	{
		return $this->dispatch($job, $job->data);
	}
	
	protected function dispatch(BorhanBatchJob $job, BorhanEventNotificationDispatchJobData $data)
	{
		$this->updateJob($job, "Dispatch template [$data->templateId]", BorhanBatchJobStatus::QUEUED);
		
		$eventNotificationPlugin = BorhanEventNotificationClientPlugin::get(self::$kClient);
		$eventNotificationTemplate = $eventNotificationPlugin->eventNotificationTemplate->get($data->templateId);
		
		$engine = $this->getEngine($job->jobSubType);
		if(!$engine)
			return $this->closeJob($job, BorhanBatchJobErrorTypes::APP, BorhanBatchJobAppErrors::ENGINE_NOT_FOUND, "Engine not found", BorhanBatchJobStatus::FAILED);
		
		$this->impersonate($job->partnerId);
		$engine->dispatch($eventNotificationTemplate, $data);
		$this->unimpersonate();
		
		return $this->closeJob($job, null, null, "Dispatched", BorhanBatchJobStatus::FINISHED, $data);
	}

	/**
	 * @param BorhanEventNotificationTemplateType $type
	 * @return KDispatchEventNotificationEngine
	 */
	protected function getEngine($type)
	{
		return BorhanPluginManager::loadObject('KDispatchEventNotificationEngine', $type);
	}
}

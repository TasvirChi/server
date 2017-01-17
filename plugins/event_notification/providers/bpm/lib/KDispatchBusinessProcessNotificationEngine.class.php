<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage Scheduler
 */
class KDispatchBusinessProcessNotificationEngine extends KDispatchEventNotificationEngine
{
	/**
	 * @param BorhanBusinessProcessServer $server
	 * @return kBusinessProcessProvider
	 */
	public function getBusinessProcessProvider(BorhanBusinessProcessServer $server)
	{
		$provider = kBusinessProcessProvider::get($server);
		$provider->enableDebug(true);
		
		return $provider;
	}
	
	/* (non-PHPdoc)
	 * @see KDispatchEventNotificationEngine::dispatch()
	 */
	public function dispatch(BorhanEventNotificationTemplate $eventNotificationTemplate, BorhanEventNotificationDispatchJobData &$data)
	{
		$job = KJobHandlerWorker::getCurrentJob();
	
		$variables = array();
		if(is_array($data->contentParameters) && count($data->contentParameters))
		{
			foreach($data->contentParameters as $contentParameter)
			{
				/* @var $contentParameter BorhanKeyValue */
				$variables[$contentParameter->key] = $contentParameter->value;
			}		
		}
		
		switch ($job->jobSubType)
		{
			case BorhanEventNotificationTemplateType::BPM_START:
				return $this->startBusinessProcess($eventNotificationTemplate, $data, $variables);
				
			case BorhanEventNotificationTemplateType::BPM_SIGNAL:
				return $this->signalCase($eventNotificationTemplate, $data, $variables);
				
			case BorhanEventNotificationTemplateType::BPM_ABORT:
				return $this->abortCase($eventNotificationTemplate, $data);
		}
	}

	/**
	 * @param BorhanBusinessProcessStartNotificationTemplate $template
	 * @param BorhanBusinessProcessNotificationDispatchJobData $data
	 */
	public function startBusinessProcess(BorhanBusinessProcessStartNotificationTemplate $template, BorhanBusinessProcessNotificationDispatchJobData &$data, $variables)
	{	
		$provider = $this->getBusinessProcessProvider($data->server);
		BorhanLog::info("Starting business-process [{$template->processId}] with variables [" . print_r($variables, true) . "]");
		$data->caseId = $provider->startBusinessProcess($template->processId, $variables);
		BorhanLog::info("Started business-process case [{$data->caseId}]");
	}

	/**
	 * @param BorhanBusinessProcessSignalNotificationTemplate $template
	 * @param BorhanBusinessProcessNotificationDispatchJobData $data
	 */
	public function signalCase(BorhanBusinessProcessSignalNotificationTemplate $template, BorhanBusinessProcessNotificationDispatchJobData &$data, $variables)
	{
		$provider = $this->getBusinessProcessProvider($data->server);
		BorhanLog::info("Signaling business-process [{$template->processId}] case [{$data->caseId}] with message [{$template->message}] on blocking event [{$template->eventId}]");
		$provider->signalCase($data->caseId, $template->eventId, $template->message, $variables);
	}

	/**
	 * @param BorhanBusinessProcessStartNotificationTemplate $template
	 * @param BorhanBusinessProcessNotificationDispatchJobData $data
	 */
	public function abortCase(BorhanBusinessProcessAbortNotificationTemplate $template, BorhanBusinessProcessNotificationDispatchJobData &$data)
	{
		$provider = $this->getBusinessProcessProvider($data->server);
		BorhanLog::info("Aborting business-process [{$template->processId}] case [{$data->caseId}]");
		$provider->abortCase($data->caseId);
	}
}

<?php
/**
 * @package plugins.integration
 * @subpackage lib.events
 */
class kIntegrationJobClosedEvent extends BorhanEvent implements IBorhanObjectRelatedEvent, IBorhanBatchJobRelatedEvent, IBorhanContinualEvent
{
	const EVENT_CONSUMER = 'kIntegrationJobClosedEventConsumer';

	/**
	 * @var BatchJob
	 */
	private $batchJob;
	
	/**
	 * @param BaseObject $object
	 */
	public function __construct(BatchJob $batchJob)
	{
		$this->batchJob = $batchJob;
		
		BorhanLog::debug("Event [" . get_class($this) . "] batch-job id [" . $batchJob->getId() . "] status [" . $batchJob->getStatus() . "]");
	}
	
	/* (non-PHPdoc)
	 * @see BorhanEvent::getConsumerInterface()
	 */
	public function getConsumerInterface()
	{
		return self::EVENT_CONSUMER;
	}

	/* (non-PHPdoc)
	 * @see BorhanEvent::doConsume()
	 */
	protected function doConsume(BorhanEventConsumer $consumer)
	{
		if(!$consumer->shouldConsumeIntegrationCloseEvent($this->object, $this->modifiedColumns))
			return true;
			
		BorhanLog::debug('consumer [' . get_class($consumer) . '] started handling [' . get_class($this) . '] batch-job id [' . $this->batchJob->getId() . '] status [' . $this->batchJob->getStatus() . ']');
		$result = $consumer->integrationJobClosed($this->batchJob);
		BorhanLog::debug('consumer [' . get_class($consumer) . '] finished handling [' . get_class($this) . '] batch-job id [' . $this->batchJob->getId() . '] status [' . $this->batchJob->getStatus() . ']');
		return $result;
	}

	/**
	 * @return BatchJob
	 */
	public function getBatchJob()
	{
		return $this->batchJob;
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectRelatedEvent::getObject()
	 */
	public function getObject()
	{
		return $this->batchJob->getObject();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanEvent::getScope()
	 */
	public function getScope()
	{
		$scope = parent::getScope();
		$scope->setPartnerId($this->batchJob->getPartnerId());
		
		return $scope;
	}
}

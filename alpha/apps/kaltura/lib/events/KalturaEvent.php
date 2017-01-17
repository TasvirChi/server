<?php
/**
 * @package Core
 * @subpackage events
 */
abstract class BorhanEvent
{
	/**
	 * @return string - name of consumer interface
	 */
	public abstract function getConsumerInterface();
	
	/**
	 * Executes the consumer
	 * @param BorhanEventConsumer $consumer
	 * @return bool true if should continue to the next consumer
	 */
	protected abstract function doConsume(BorhanEventConsumer $consumer);

	/**
	 * @param kGenericEventConsumer $consumer
	 * @return bool true if should continue to the next consumer
	 */
	protected function consumeGeneric(kGenericEventConsumer $consumer)
	{
		if(!$consumer->shouldConsumeEvent($this))
			return true;

		BorhanLog::debug('consumer [' . get_class($consumer) . '] started handling [' . get_class($this) . ']');
		$result = $consumer->consumeEvent($this);
		BorhanLog::debug('consumer [' . get_class($consumer) . '] finished handling [' . get_class($this) . ']');
		return $result;
	}
	
	/**
	 * Validate the consumer type and executes it
	 * @param BorhanEventConsumer $consumer
	 * @return bool true if should continue to the next consumer
	 */
	public final function consume(BorhanEventConsumer $consumer)
	{
		$consumerType = $this->getConsumerInterface();	
		if($consumer instanceof $consumerType)
			return $this->doConsume($consumer);	
		elseif($consumer instanceof kGenericEventConsumer)
			return $this->consumeGeneric($consumer);
			
		return true;
	}
	
	/**
	 * @return string
	 */
	public function getKey()
	{
		return null;
	}
	
	/**
	 * @return kEventScope
	 */
	public function getScope()
	{
		return new kEventScope($this);
	}
	
	/**
	 * @return int of enum EventPriority
	 */
	public function getPriority()
	{
		return EventPriority::NORMAL;
	}
}
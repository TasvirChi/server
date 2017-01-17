<?php
/**
 * Consume any type of event
 * @package Core
 * @subpackage events
 */
interface kGenericEventConsumer extends BorhanEventConsumer
{
	/**
	 * @param BorhanEvent $event
	 * @return bool true if should continue to the next consumer
	 */
	public function consumeEvent(BorhanEvent $event);
	
	/**
	 * @param BorhanEvent $event
	 * @return bool true if the consumer should handle the event
	 */
	public function shouldConsumeEvent(BorhanEvent $event);
}
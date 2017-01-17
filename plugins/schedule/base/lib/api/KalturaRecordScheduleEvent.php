<?php
/**
 * @package plugins.schedule
 * @subpackage api.objects
 */
class BorhanRecordScheduleEvent extends BorhanEntryScheduleEvent
{
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($sourceObject = null, $propertiesToSkip = array())
	{
		if(is_null($sourceObject))
		{
			$sourceObject = new RecordScheduleEvent();
		}
		
		return parent::toObject($sourceObject, $propertiesToSkip);
	}
	
	/**
	 * {@inheritDoc}
	 * @see BorhanScheduleEvent::getScheduleEventType()
	 */
	public function getScheduleEventType()
	{
		return ScheduleEventType::RECORD;
	}
}
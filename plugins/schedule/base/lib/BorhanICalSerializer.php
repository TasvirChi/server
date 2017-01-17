<?php

class BorhanICalSerializer extends BorhanSerializer
{
	private $calendar;
	
	public function __construct()
	{
		$this->calendar = new kSchedulingICalCalendar();
	}
	/**
	 * {@inheritDoc}
	 * @see BorhanSerializer::setHttpHeaders()
	 */
	public function setHttpHeaders()
	{
		header("Content-Type: text/calendar; charset=UTF-8");		
	}

	/**
	 * {@inheritDoc}
	 * @see BorhanSerializer::getHeader()
	 */
	public function getHeader() 
	{
		return $this->calendar->begin();
	}


	/**
	 * {@inheritDoc}
	 * @see BorhanSerializer::serialize()
	 */
	public function serialize($object)
	{
		if($object instanceof BorhanScheduleEvent)
		{
			$event = kSchedulingICalEvent::fromObject($object);
			return $event->write();
		}
		elseif($object instanceof BorhanScheduleEventArray)
		{
			$ret = '';
			foreach($object as $item)
			{
				$ret .= $this->serialize($item);
			}
			return $ret;
		}
		elseif($object instanceof BorhanScheduleEventListResponse)
		{
			$ret = $this->serialize($object->objects);
			$ret .= $this->calendar->writeField('X-BORHAN-TOTAL-COUNT', $object->totalCount);
			return $ret;
		}
		elseif($object instanceof BorhanAPIException)
		{
			$ret = $this->calendar->writeField('BEGIN', 'VERROR');
			$ret .= $this->calendar->writeField('X-BORHAN-CODE', $object->getCode());
			$ret .= $this->calendar->writeField('X-BORHAN-MESSAGE', $object->getMessage());
			$ret .= $this->calendar->writeField('X-BORHAN-ARGUMENTS', implode(';', $object->getArgs()));
			$ret .= $this->calendar->writeField('END', 'VERROR');
			return $ret;
		}
		else
		{
			$ret = $this->calendar->writeField('BEGIN', get_class($object));
			$ret .= $this->calendar->writeField('END', get_class($object));
			
			return $ret;
		}
	}
	
	/**
	 * {@inheritDoc}
	 * @see BorhanSerializer::getFooter()
	 */
	public function getFooter($execTime = null)
	{
		if($execTime)
			$this->calendar->writeField('x-borhan-execution-time', $execTime);
		
		return $this->calendar->end();
	}
}
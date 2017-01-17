<?php

class kSchedulingICalRule extends kSchedulingICalComponent
{
	private static $stringFields = array(
		'name',
		'count',
		'interval',
		'bySecond',
		'byMinute',
		'byHour',
		'byDay',
		'byMonthDay',
		'byYearDay',
		'byWeekNumber' => 'byweekno',
		'byMonth',
		'byOffset' => 'bysetpos',
		'weekStartDay' => 'wkst',
	);
	
	/**
	 * {@inheritDoc}
	 * @see kSchedulingICalComponent::getLineDelimiter()
	 */
	protected function getLineDelimiter()
	{
		return ";";
	}
	
	/**
	 * {@inheritDoc}
	 * @see kSchedulingICalComponent::getFieldDelimiter()
	 */
	protected function getFieldDelimiter()
	{
		return '=';
	}
	
	/**
	 * {@inheritDoc}
	 * @see kSchedulingICalComponent::getType()
	 */
	protected function getType()
	{
		return 'RRULE';
	}

	/**
	 * {@inheritDoc}
	 * @see kSchedulingICalComponent::toObject()
	 */
	public function toObject()
	{
		$rule = new BorhanScheduleEventRecurrence();
		$rule->frequency = constant('BorhanScheduleEventRecurrenceFrequency::' . $this->getField('freq'));
		$rule->until = kSchedulingICal::parseDate($this->getField('until'));

		$strings = array(
			'name',
			'count',
			'interval',
			'bySecond',
			'byMinute',
			'byHour',
			'byDay',
			'byMonthDay',
			'byYearDay',
			'byWeekNumber' => 'byweekno',
			'byMonth',
			'byOffset' => 'bysetpos',
			'weekStartDay' => 'wkst',
		);
		foreach(self::$stringFields as $attribute => $field)
		{
			if(is_numeric($attribute))
				$attribute = $field;
			
			$rule->$attribute = $this->getField($field);
		}
		
		return $rule;
	}
	
	/**
	 * @param BorhanScheduleEventRecurrence $rule
	 * @return kSchedulingICalRule
	 */
	public static function fromObject(BorhanScheduleEventRecurrence $rule)
	{
		$object = new kSchedulingICalRule();

		$frequencyTypes = array(
			BorhanScheduleEventRecurrenceFrequency::SECONDLY => 'SECONDLY',
			BorhanScheduleEventRecurrenceFrequency::MINUTELY => 'MINUTELY',
			BorhanScheduleEventRecurrenceFrequency::HOURLY => 'HOURLY',
			BorhanScheduleEventRecurrenceFrequency::DAILY => 'DAILY',
			BorhanScheduleEventRecurrenceFrequency::WEEKLY => 'WEEKLY',
			BorhanScheduleEventRecurrenceFrequency::MONTHLY => 'MONTHLY',
			BorhanScheduleEventRecurrenceFrequency::YEARLY => 'YEARLY',
		);
		
		if($rule->frequency && isset($frequencyTypes[$rule->frequency]))
			$object->setField('freq', $frequencyTypes[$rule->frequency]);

		if($rule->until)
			$object->setField('until', kSchedulingICal::formatDate($rule->until));

		foreach(self::$stringFields as $attribute => $field)
		{
			if(is_numeric($attribute))
				$attribute = $field;
			
			if($rule->$attribute)
				$object->setField($field, $rule->$attribute);
		}
		
		return $object;
	}
	
	public function getBody()
	{
		$lines = array();
		foreach($this->fields as $field => $value)
			$lines[] = $field . $this->getFieldDelimiter() . $value;
		
		return implode($this->getLineDelimiter(), $lines);
	}
	
	/**
	 * {@inheritDoc}
	 * @see kSchedulingICalComponent::write()
	 */
	public function write()
	{
		return $this->writeBody();
	}
}

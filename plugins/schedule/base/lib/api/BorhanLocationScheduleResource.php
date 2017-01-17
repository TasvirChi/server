<?php
/**
 * @package plugins.schedule
 * @subpackage api.objects
 */
class BorhanLocationScheduleResource extends BorhanScheduleResource
{
	/**
	 * {@inheritDoc}
	 * @see BorhanScheduleResource::getScheduleResourceType()
	 */
	protected function getScheduleResourceType()
	{
		return ScheduleResourceType::LOCATION;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($sourceObject = null, $propertiesToSkip = array())
	{
		if(is_null($sourceObject))
		{
			$sourceObject = new LocationScheduleResource();
		}
		
		return parent::toObject($sourceObject, $propertiesToSkip);
	}
}
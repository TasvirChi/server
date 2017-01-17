<?php
/**
 * @package plugins.schedule
 * @subpackage api.filters
 */
class BorhanCameraScheduleResourceFilter extends BorhanCameraScheduleResourceBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanScheduleResourceFilter::getListResponseType()
	 */
	protected function getListResponseType()
	{
		return ScheduleResourceType::CAMERA;
	}
}

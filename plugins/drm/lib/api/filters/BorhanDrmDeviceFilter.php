<?php
/**
 * @package plugins.drm
 * @subpackage api.filters
 */
class BorhanDrmDeviceFilter extends BorhanDrmDeviceBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DrmDeviceFilter();
	}
}

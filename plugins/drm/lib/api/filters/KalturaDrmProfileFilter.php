<?php
/**
 * @package plugins.drm
 * @subpackage api.filters
 */
class BorhanDrmProfileFilter extends BorhanDrmProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DrmProfileFilter();
	}
}

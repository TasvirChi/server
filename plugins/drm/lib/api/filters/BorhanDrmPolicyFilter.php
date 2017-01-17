<?php
/**
 * @package plugins.drm
 * @subpackage api.filters
 */
class BorhanDrmPolicyFilter extends BorhanDrmPolicyBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DrmPolicyFilter();
	}
}

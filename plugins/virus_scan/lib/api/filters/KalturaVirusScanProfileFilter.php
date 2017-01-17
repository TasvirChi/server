<?php
/**
 * @package plugins.virusScan
 * @subpackage api.filters
 */
class BorhanVirusScanProfileFilter extends BorhanVirusScanProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new VirusScanProfileFilter();
	}
}

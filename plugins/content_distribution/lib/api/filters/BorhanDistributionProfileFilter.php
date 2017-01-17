<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.filters
 */
class BorhanDistributionProfileFilter extends BorhanDistributionProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DistributionProfileFilter();
	}
}

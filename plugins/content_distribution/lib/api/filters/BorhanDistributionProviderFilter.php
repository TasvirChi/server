<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.filters
 */
class BorhanDistributionProviderFilter extends BorhanDistributionProviderBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		throw new Exception("Distribution providers can't be filtered");
	}
}

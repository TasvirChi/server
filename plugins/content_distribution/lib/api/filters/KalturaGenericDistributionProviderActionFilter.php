<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.filters
 */
class BorhanGenericDistributionProviderActionFilter extends BorhanGenericDistributionProviderActionBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new GenericDistributionProviderActionFilter();
	}
}

<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanBaseSyndicationFeedFilter extends BorhanBaseSyndicationFeedBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new syndicationFeedFilter();
	}
}

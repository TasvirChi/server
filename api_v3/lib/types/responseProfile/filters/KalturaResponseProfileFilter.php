<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanResponseProfileFilter extends BorhanResponseProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ResponseProfileFilter();
	}
}

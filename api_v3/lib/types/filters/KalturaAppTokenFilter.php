<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanAppTokenFilter extends BorhanAppTokenBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new appTokenFilter();
	}
}

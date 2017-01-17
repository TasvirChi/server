<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanWidgetFilter extends BorhanWidgetBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new widgetFilter();
	}
}

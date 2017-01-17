<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanControlPanelCommandFilter extends BorhanControlPanelCommandBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ControlPanelCommandFilter();
	}
}

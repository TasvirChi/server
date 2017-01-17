<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanUiConfFilter extends BorhanUiConfBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new uiConfFilter();
	}
}

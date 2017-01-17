<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanReportFilter extends BorhanReportBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new ReportFilter();
	}
}

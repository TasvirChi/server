<?php
/**
 * @package plugins.systemPartner
 * @subpackage api.objects
 */
class BorhanSystemPartnerUsageFilter extends BorhanFilter
{
	/**
	 * Date range from
	 * 
	 * @var int
	 */
	public $fromDate;
	
	/**
	 * Date range to
	 * 
	 * @var int
	 */
	public $toDate;
	
	/**
	 * Time zone offset
	 * @var int
	 */
	public $timezoneOffset;

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new partnerFilter();
	}
}
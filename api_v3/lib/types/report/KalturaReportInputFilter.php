<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanReportInputFilter extends BorhanReportInputBaseFilter 
{
	/**
	 * Search keywords to filter objects
	 * 
	 * @var string
	 */
	public $keywords;
	
	/**
	 * Search keywords in onjects tags
	 * 
	 * @var bool
	 */
	public $searchInTags;	

	/**
	 * Search keywords in onjects admin tags
	 * 
	 * @var bool
	 * @deprecated
	 */
	public $searchInAdminTags;	
	
	/**
	 * Search onjects in specified categories
	 * 
	 * @var string
	 */
	public $categories;
	
	/**
	 * Time zone offset in minutes
	 * 
	 * @var int
	 */
	public $timeZoneOffset = 0;
	
	/**
	 * Aggregated results according to interval
	 * 
	 * @var BorhanReportInterval
	 */
	public $interval;
	
	/* (non-PHPdoc)
	 * @see BorhanReportInputBaseFilter::toReportsInputFilter()
	 */
	public function toReportsInputFilter($reportInputFilter = null)
	{
		$reportInputFilter = parent::toReportsInputFilter($reportInputFilter);
		
		$reportInputFilter->keywords = $this->keywords;
		$reportInputFilter->search_in_tags= $this->searchInTags;
		$reportInputFilter->search_in_admin_tags = $this->searchInAdminTags;
		$reportInputFilter->categories = $this->categories;
		$reportInputFilter->timeZoneOffset = $this->timeZoneOffset;
		$reportInputFilter->interval = $this->interval;
		
		return $reportInputFilter;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanReportInputBaseFilter::fromReportsInputFilter()
	 */
	public function fromReportsInputFilter($reportInputFilter )
	{
		parent::fromReportsInputFilter($reportInputFilter);
		
		$this->keywords = $reportInputFilter->keywords ;
		$this->searchInTags = $reportInputFilter->search_in_tags ;
		$this->searchInAdminTags = $reportInputFilter->search_in_admin_tags ;
		$this->categories = $reportInputFilter->categories;
		$this->timeZoneOffset = $reportInputFilter->timeZoneOffset;
		
		return $this;
	}	
}

<?php


class WSLiveReportInputFilter extends WSBaseObject
{	
	function getBorhanObject() {
		return new BorhanLiveReportInputFilter();
	}
				
	/**
	 * @var string
	 **/
	public $entryIds;
	
	/**
	 * @var long
	 **/
	public $fromTime;
	
	/**
	 * @var long
	 **/
	public $toTime;
	
	/**
	 * @var boolean
	 **/
	public $live;
	
	/**
	 * @var long
	 **/
	public $partnerId;
	
	/**
	 * @var string
	 */
	public $orderBy;
	
}



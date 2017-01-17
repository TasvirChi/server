<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveReportInputFilter extends BorhanObject
{	
	/**
	 * @var string
	 **/
	public $entryIds;
	
	/**
	 * @var time
	 **/
	public $fromTime;
	
	/**
	 * @var time
	 **/
	public $toTime;
	
	/**
	 * @var BorhanNullableBoolean
	 **/
	public $live;
	
	/**
	 * @var BorhanLiveReportOrderBy
	 */
	public $orderBy;
	
	public function getWSObject() {
		$obj = new WSLiveReportInputFilter();
		$obj->fromBorhanObject($this);
		return $obj;
	}
}



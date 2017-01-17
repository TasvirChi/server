<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveStatsListResponse extends BorhanListResponse
{				
	/**
	 *
	 * @var BorhanLiveStats
	 **/
	public $objects;
	
	public function getWSObject() {
		$obj = new WSLiveEntriesListResponse();
		$obj->fromBorhanObject($this);
		return $obj;
	}
	
}



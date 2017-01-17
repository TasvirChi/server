<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanGeoTimeLiveStats extends BorhanEntryLiveStats
{	
	/**
	 * @var BorhanCoordinate
	 **/
	public $city;
	
	/**
	 * @var BorhanCoordinate
	 **/
	public $country;
	
	public function getWSObject() {
		$obj = new WSGeoTimeLiveStats();
		$obj->fromBorhanObject($this);
		return $obj;
	}
}



<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanEntryReferrerLiveStats extends BorhanEntryLiveStats
{			
	/**
	 * @var string
	 **/
	public $referrer;
	
	public function getWSObject() {
		$obj = new WSEntryReferrerLiveStats();
		$obj->fromBorhanObject($this);
		return $obj;
	}
}



<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanEntryLiveStats extends BorhanLiveStats
{				
	/**
	 * @var string
	 **/
	public $entryId;
	
	/**
	 * @var int
	 */
	public $peakAudience;

	/**
	 * @var int
	 */
	public $peakDvrAudience;
	
	public function getWSObject() {
		$obj = new WSEntryLiveStats();
		$obj->fromBorhanObject($this);
		return $obj;
	}
	
}



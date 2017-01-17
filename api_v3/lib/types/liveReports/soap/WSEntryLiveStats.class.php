<?php


class WSEntryLiveStats extends WSLiveStats
{				
	function getBorhanObject() {
		return new BorhanEntryLiveStats();
	}
	
	/**
	 * @var string
	 **/
	public $entryId;
	
	/**
	 * @var long
	 */
	public $peakAudience;

	/**
	 * @var long
	 */
	public $peakDvrAudience;
}



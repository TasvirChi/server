<?php


class WSEntryReferrerLiveStats extends WSEntryLiveStats
{			
	function getBorhanObject() {
		return new BorhanEntryReferrerLiveStats();
	}
	
	/**
	 * @var string
	 **/
	public $referrer;
	
}



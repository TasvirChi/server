<?php


class WSLiveEventsListResponse extends WSBaseObject
{				
	function getBorhanObject() {
		return new BorhanLiveEventsListResponse();
	}
	
	/**
	 * @var array
	 **/
	public $objects;
	
	/**
	 * @var int
	 **/
	public $totalCount;
	
}



<?php


class WSLiveStats extends WSBaseObject
{				
	function getBorhanObject() {
		return new BorhanLiveStats();
	}
	
	/**
	 * @var long
	 **/
	public $audience;

	/**
	 * @var long
	 **/
	public $dvrAudience;

	/**
	 * @var float
	 **/
	public $avgBitrate;
	
	/**
	 * @var long
	 **/
	public $bufferTime;
	
	/**
	 * @var long
	 **/
	public $plays;
	
	/**
	 * @var long
	 **/
	public $secondsViewed;
	
	/**
	 * @var long
	 **/
	public $startEvent;
	
	/**
	 * @var long
	 **/
	public $timestamp;
	
}



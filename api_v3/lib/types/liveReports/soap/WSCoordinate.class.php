<?php

class WSCoordinate extends WSBaseObject
{	
	function getBorhanObject() {
		return new BorhanCoordinate();
	}
				
	/**
	 * @var float
	 **/
	public $latitude;
	
	/**
	 * @var float
	 **/
	public $longitude;
	
	/**
	 * @var string
	 **/
	public $name;
	
}



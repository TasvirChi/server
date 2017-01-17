<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanCoordinate extends BorhanObject
{	
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
	
	public function getWSObject() {
		$obj = new WSCoordinate();
		$obj->fromBorhanObject($this);
		return $obj;
	}
}



<?php
/**
 * @package plugins.tagSearch
 * @subpackage api.objects
 */
class BorhanTagListResponse extends BorhanListResponse
{
    /**
	 * @var BorhanTagArray
	 * @readonly
	 */
	public $objects;

	
	public function __construct()
	{
	    $this->objects = array();
	    $this->totalCount = count($this->objects);
	}
}
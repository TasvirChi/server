<?php

/**
 * @package api
 * @subpackage objects
 */
class BorhanObjectArray extends BorhanTypedArray
{
	public function __construct()
	{
		parent::__construct('BorhanObject');
	}
}
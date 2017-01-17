<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFileExistsResponse extends BorhanObject 
{
	/**
	 * Indicates if the file exists
	 * 
	 * @var bool
	 */
	public $exists;
	
	
	/**
	 * Indicates if the file size is right
	 * 
	 * @var bool
	 */
	public $sizeOk;
}
<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUploadResponse extends BorhanObject
{
	/**
	 * @var string
	 */
	public $uploadTokenId;

	/**
	 * @var int
	 */
	public $fileSize;
	
	/**
	 * 
	 * @var BorhanUploadErrorCode
	 */
	public $errorCode;
	
	/**
	 * 
	 * @var string
	 */
	public $errorDescription;
	
}
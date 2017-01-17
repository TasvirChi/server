<?php
/**
 * Is a unified way to add content to Borhan whether it's an uploaded file, webcam recording, imported URL or existing file sync.
 * 
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanContentResource extends BorhanResource 
{
	public function validateAsset(asset $dbAsset)
	{
	
	}
}
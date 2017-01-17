<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUrlTokenizerLimeLight extends BorhanUrlTokenizer {

	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kLimeLightUrlTokenizer();
			
		parent::toObject($dbObject, $skip);
	
		return $dbObject;
	}
}

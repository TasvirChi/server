<?php
/**
 * Represents the current session user e-mail address context
 * 
 * @package api
 * @subpackage objects
 */
class BorhanUserEmailContextField extends BorhanStringField
{
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kUserEmailContextField();
			
		return parent::toObject($dbObject, $skip);
	}
}
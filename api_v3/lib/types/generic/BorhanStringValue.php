<?php
/**
 * A string representation to return an array of strings
 * 
 * @see BorhanStringValueArray
 * @package api
 * @subpackage objects
 */
class BorhanStringValue extends BorhanValue
{
	/**
	 * @var string
	 */
    public $value;

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kStringValue();
			
		return parent::toObject($dbObject, $skip);
	}
}
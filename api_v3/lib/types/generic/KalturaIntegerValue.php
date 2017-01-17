<?php
/**
 * An int representation to return an array of ints
 * 
 * @see BorhanIntegerValueArray
 * @package api
 * @subpackage objects
 */
class BorhanIntegerValue extends BorhanValue
{
	/**
	 * @var int
	 */
    public $value;

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kIntegerValue();
			
		return parent::toObject($dbObject, $skip);
	}
}
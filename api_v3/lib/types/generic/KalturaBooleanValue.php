<?php
/**
 * A boolean representation to return an array of booleans
 * 
 * @see BorhanBooleanValueArray
 * @package api
 * @subpackage objects
 */
class BorhanBooleanValue extends BorhanValue
{
	/**
	 * @var bool
	 */
    public $value;

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kBooleanValue();
			
		return parent::toObject($dbObject, $skip);
	}
}
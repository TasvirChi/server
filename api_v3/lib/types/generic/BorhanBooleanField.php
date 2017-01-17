<?php
/**
 * A boolean representation to return evaluated dynamic value
 * 
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanBooleanField extends BorhanBooleanValue
{
	/* (non-PHPdoc)
	 * @see BorhanIntegerValue::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!is_null($this->value) && !($this->value instanceof BorhanNullField))
			throw new BorhanAPIException(BorhanErrors::PROPERTY_VALIDATION_NOT_UPDATABLE, $this->getFormattedPropertyNameWithClassName('value'));

		return parent::toObject($dbObject, $skip);
	}
}
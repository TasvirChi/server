<?php
/**
 * An int representation to return evaluated dynamic value
 * 
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanIntegerField extends BorhanIntegerValue
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
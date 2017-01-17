<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanScope extends BorhanObject
{
	public function toObject($objectToFill = null, $propsToSkip = array())
	{
		if (is_null($objectToFill))
			$objectToFill = new kScope();

		return parent::toObject($objectToFill, $propsToSkip);
	}
}
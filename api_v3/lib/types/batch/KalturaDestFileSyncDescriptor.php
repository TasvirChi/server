<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanDestFileSyncDescriptor extends BorhanFileSyncDescriptor
{
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kDestFileSyncDescriptor();
			
		return parent::toObject($dbObject, $skip);
	}
}
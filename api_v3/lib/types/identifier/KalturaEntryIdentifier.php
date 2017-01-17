<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanEntryIdentifier extends BorhanObjectIdentifier
{
	/**
	 * Identifier of the object
	 * @var BorhanEntryIdentifierField
	 */
	public $identifier;
	
	/* (non-PHPdoc)
	 * @see BorhanObjectIdentifier::toObject()
	 */
	public function toObject ($dbObject = null, $propsToSkip = array())
	{
		if (!$dbObject)
			$dbObject = new kEntryIdentifier();

		return parent::toObject($dbObject, $propsToSkip);
	}
	
	private static $map_between_objects = array(
			"identifier",
		);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
}
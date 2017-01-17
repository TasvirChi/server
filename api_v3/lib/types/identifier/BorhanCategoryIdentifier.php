<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCategoryIdentifier extends BorhanObjectIdentifier
{
	/**
	 * Identifier of the object
	 * @var BorhanCategoryIdentifierField
	 */
	public $identifier;
	
	/* (non-PHPdoc)
	 * @see BorhanObjectIdentifier::toObject()
	 */
	public function toObject ($dbObject = null, $propsToSkip = array())
	{
		if (!$dbObject)
			$dbObject = new kCategoryIdentifier();

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
<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanMediaServerStatus extends BorhanObject
{
	private static $mapBetweenObjects = array
	(
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}
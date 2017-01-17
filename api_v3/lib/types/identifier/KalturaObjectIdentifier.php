<?php
/**
 * Configuration for extended item in the Borhan MRSS feeds
 *
 * @package api
 * @subpackage objects
 */
abstract class BorhanObjectIdentifier extends BorhanObject
{
	/**
	 * Comma separated string of enum values denoting which features of the item need to be included in the MRSS 
	 * @dynamicType BorhanObjectFeatureType
	 * @var string
	 */
	public $extendedFeatures;
	
	
	private static $map_between_objects = array(
		"extendedFeatures",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

}
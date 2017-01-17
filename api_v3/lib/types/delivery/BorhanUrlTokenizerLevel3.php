<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUrlTokenizerLevel3 extends BorhanUrlTokenizer {

	/**
	 * paramName
	 *
	 * @var string
	 */
	public $paramName;
	
	/**
	 * expiryName
	 *
	 * @var string
	 */
	public $expiryName;
	
	/**
	 * gen
	 *
	 * @var string
	 */
	public $gen;
	
	private static $map_between_objects = array
	(
			"paramName" => "name",
			"expiryName",
			"gen"
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kLevel3UrlTokenizer();
			
		parent::toObject($dbObject, $skip);
	
		return $dbObject;
	}
}

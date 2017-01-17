<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPlaybackContext extends BorhanObject{

	/**
	 * @var BorhanPlaybackSourceArray
	 */
	public $sources;
    
	/**
	 * @var BorhanFlavorAssetArray
	 */
	public $flavorAssets;

	/**
	 * Array of actions as received from the rules that invalidated
	 * @var BorhanRuleActionArray
	 */
	public $actions;

	/**
	 * Array of actions as received from the rules that invalidated
	 * @var BorhanAccessControlMessageArray
	 */
	public $messages;

	private static $mapBetweenObjects = array
	(
		'flavorAssets',
		'sources',
		'messages',
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}
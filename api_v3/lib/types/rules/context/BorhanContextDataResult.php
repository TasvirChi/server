<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanContextDataResult extends BorhanObject
{	
	/**
	 * Array of messages as received from the rules that invalidated
	 * @var BorhanStringArray
	 */
	public $messages;
	
	/**
	 * Array of actions as received from the rules that invalidated
	 * @var BorhanRuleActionArray
	 */
	public $actions;

	private static $mapBetweenObjects = array
	(
		'messages',
		'actions',
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}
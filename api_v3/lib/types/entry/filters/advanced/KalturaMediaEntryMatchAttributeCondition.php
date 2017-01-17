<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanMediaEntry attributes. Use BorhanMediaEntryMatchAttribute enum to provide attribute name.
*/
class BorhanMediaEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanMediaEntryMatchAttribute
	 */
	public $attribute;

	private static $mapBetweenObjects = array
	(
		"attribute" => "attribute",
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects() , self::$mapBetweenObjects);
	}

	protected function getIndexClass()
	{
		return 'entryIndex';
	}
}


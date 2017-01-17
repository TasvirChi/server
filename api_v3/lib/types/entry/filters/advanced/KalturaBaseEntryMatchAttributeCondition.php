<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanBaseEntry attributes. Use BorhanBaseEntryMatchAttribute enum to provide attribute name.
*/
class BorhanBaseEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanBaseEntryMatchAttribute
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


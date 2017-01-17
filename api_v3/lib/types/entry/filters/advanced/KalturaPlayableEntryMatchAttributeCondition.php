<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanPlayableEntry attributes. Use BorhanPlayableEntryMatchAttribute enum to provide attribute name.
*/
class BorhanPlayableEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanPlayableEntryMatchAttribute
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


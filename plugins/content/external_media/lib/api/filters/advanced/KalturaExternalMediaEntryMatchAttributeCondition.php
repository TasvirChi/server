<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanExternalMediaEntry attributes. Use BorhanExternalMediaEntryMatchAttribute enum to provide attribute name.
*/
class BorhanExternalMediaEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanExternalMediaEntryMatchAttribute
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


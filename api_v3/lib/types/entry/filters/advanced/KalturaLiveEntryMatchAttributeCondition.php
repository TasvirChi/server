<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveEntry attributes. Use BorhanLiveEntryMatchAttribute enum to provide attribute name.
*/
class BorhanLiveEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanLiveEntryMatchAttribute
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


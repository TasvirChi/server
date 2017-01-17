<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveStreamEntry attributes. Use BorhanLiveStreamEntryMatchAttribute enum to provide attribute name.
*/
class BorhanLiveStreamEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanLiveStreamEntryMatchAttribute
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


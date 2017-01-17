<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveStreamEntry attributes. Use BorhanLiveStreamEntryCompareAttribute enum to provide attribute name.
*/
class BorhanLiveStreamEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanLiveStreamEntryCompareAttribute
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


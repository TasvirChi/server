<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveEntry attributes. Use BorhanLiveEntryCompareAttribute enum to provide attribute name.
*/
class BorhanLiveEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanLiveEntryCompareAttribute
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


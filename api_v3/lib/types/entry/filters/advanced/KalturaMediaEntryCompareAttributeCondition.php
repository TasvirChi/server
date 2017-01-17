<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanMediaEntry attributes. Use BorhanMediaEntryCompareAttribute enum to provide attribute name.
*/
class BorhanMediaEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanMediaEntryCompareAttribute
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


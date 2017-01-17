<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanDataEntry attributes. Use BorhanDataEntryCompareAttribute enum to provide attribute name.
*/
class BorhanDataEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanDataEntryCompareAttribute
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


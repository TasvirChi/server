<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanBaseEntry attributes. Use BorhanBaseEntryCompareAttribute enum to provide attribute name.
*/
class BorhanBaseEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanBaseEntryCompareAttribute
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


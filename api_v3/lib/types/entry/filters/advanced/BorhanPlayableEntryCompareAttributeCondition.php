<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanPlayableEntry attributes. Use BorhanPlayableEntryCompareAttribute enum to provide attribute name.
*/
class BorhanPlayableEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanPlayableEntryCompareAttribute
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


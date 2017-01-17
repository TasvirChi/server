<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanExternalMediaEntry attributes. Use BorhanExternalMediaEntryCompareAttribute enum to provide attribute name.
*/
class BorhanExternalMediaEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanExternalMediaEntryCompareAttribute
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


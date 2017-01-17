<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanDocumentEntry attributes. Use BorhanDocumentEntryCompareAttribute enum to provide attribute name.
*/
class BorhanDocumentEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanDocumentEntryCompareAttribute
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


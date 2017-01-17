<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveStreamAdminEntry attributes. Use BorhanLiveStreamAdminEntryCompareAttribute enum to provide attribute name.
*/
class BorhanLiveStreamAdminEntryCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanLiveStreamAdminEntryCompareAttribute
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


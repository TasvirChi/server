<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveChannel attributes. Use BorhanLiveChannelCompareAttribute enum to provide attribute name.
*/
class BorhanLiveChannelCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanLiveChannelCompareAttribute
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


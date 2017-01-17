<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveChannel attributes. Use BorhanLiveChannelMatchAttribute enum to provide attribute name.
*/
class BorhanLiveChannelMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanLiveChannelMatchAttribute
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


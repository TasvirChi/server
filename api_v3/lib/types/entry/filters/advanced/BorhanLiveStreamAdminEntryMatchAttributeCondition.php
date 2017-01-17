<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanLiveStreamAdminEntry attributes. Use BorhanLiveStreamAdminEntryMatchAttribute enum to provide attribute name.
*/
class BorhanLiveStreamAdminEntryMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanLiveStreamAdminEntryMatchAttribute
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


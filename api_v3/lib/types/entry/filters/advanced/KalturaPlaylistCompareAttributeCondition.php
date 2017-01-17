<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanPlaylist attributes. Use BorhanPlaylistCompareAttribute enum to provide attribute name.
*/
class BorhanPlaylistCompareAttributeCondition extends BorhanSearchComparableAttributeCondition
{
	/**
	 * @var BorhanPlaylistCompareAttribute
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


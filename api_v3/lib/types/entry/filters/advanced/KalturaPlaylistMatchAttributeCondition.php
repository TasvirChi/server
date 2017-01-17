<?php

/**
 * Auto-generated class.
 * 
 * Used to search BorhanPlaylist attributes. Use BorhanPlaylistMatchAttribute enum to provide attribute name.
*/
class BorhanPlaylistMatchAttributeCondition extends BorhanSearchMatchAttributeCondition
{
	/**
	 * @var BorhanPlaylistMatchAttribute
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


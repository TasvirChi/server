<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveChannel extends BorhanLiveEntry
{
	/**
	 * Playlist id to be played
	 * 
	 * @var string
	 */
	public $playlistId;
	
	/**
	 * Indicates that the segments should be repeated for ever
	 * @var BorhanNullableBoolean
	 */
	public $repeat;
	
	private static $map_between_objects = array
	(
		'playlistId',
		'repeat',
	);

	/* (non-PHPdoc)
	 * @see BorhanLiveEntry::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function __construct()
	{
		parent::__construct();
		
		$this->type = BorhanEntryType::LIVE_CHANNEL;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::fromSourceType()
	 */
	protected function fromSourceType(entry $entry) 
	{
		$this->sourceType = BorhanSourceType::LIVE_CHANNEL;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::toSourceType()
	 */
	protected function toSourceType(entry $entry) 
	{
		$entry->setSource(BorhanSourceType::LIVE_CHANNEL);
	}
}

<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanLiveEntryFilter extends BorhanLiveEntryBaseFilter
{
	public function __construct()
	{
		$this->typeIn = BorhanEntryType::LIVE_CHANNEL . ',' . BorhanEntryType::LIVE_STREAM;
	}
	
	static private $map_between_objects = array
	(
		"isLive" => "_is_live",
		"isRecordedEntryIdEmpty" => "_is_recorded_entry_id_empty",
		"hasMediaServerHostname" => "_has_media_server_hostname",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/**
	 * @var BorhanNullableBoolean
	 */
	public $isLive;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $isRecordedEntryIdEmpty;

	/**
	 * @var string
	 */
	public $hasMediaServerHostname;
}

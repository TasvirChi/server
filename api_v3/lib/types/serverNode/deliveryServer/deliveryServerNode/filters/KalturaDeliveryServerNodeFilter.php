<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanDeliveryServerNodeFilter extends BorhanDeliveryServerNodeBaseFilter
{
	static private $map_between_objects = array
	(
			"playbackDomainLike" => "_like_playback_host_name",
			"playbackDomainMultiLikeOr" => "_mlikeor_playback_host_name",
			"playbackDomainMultiLikeAnd" => "_mlikeand_playback_host_name",
	);
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
}

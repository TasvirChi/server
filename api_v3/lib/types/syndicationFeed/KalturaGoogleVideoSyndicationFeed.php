<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanGoogleVideoSyndicationFeed extends BorhanBaseSyndicationFeed
{
        /**
         *
         * @var BorhanGoogleSyndicationFeedAdultValues
         */
        public $adultContent;
	
	private static $mapBetweenObjects = array
	(
    	"adultContent",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
        
        function __construct()
	{
		$this->type = BorhanSyndicationFeedType::GOOGLE_VIDEO;
	}
}
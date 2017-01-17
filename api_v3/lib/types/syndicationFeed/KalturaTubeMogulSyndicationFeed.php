<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanTubeMogulSyndicationFeed extends BorhanBaseSyndicationFeed
{
        /**
         *
         * @var BorhanTubeMogulSyndicationFeedCategories
         * @readonly
         */
        public $category;
        
	function __construct()
	{
		$this->type = BorhanSyndicationFeedType::TUBE_MOGUL;
	}
        
	private static $mapBetweenObjects = array
	(
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
        
        public function toObject($object_to_fill = null , $props_to_skip = array())
        {
            $categories = explode(',', $this->categories);
            $numCategories = array();
            foreach($categories as $category)
            {
                $numCategories[] = $this->getCategoryId($category);
            }
            $this->categories = implode(',', $numCategories);
            parent::toObject($object_to_fill);
            $this->categories = implode(',', $categories);
        }
        
        public function doFromObject($source_object, BorhanDetachedResponseProfile $responseProfile = null)
        {
            parent::doFromObject($source_object, $responseProfile);
            $categories = explode(',', $this->categories);
            $strCategories = array();
            foreach($categories as $category)
            {
                $strCategories[] = $this->getCategoryName($category);
            }
            $this->categories = implode(',', $strCategories);
        }
        
        private static $mapCategories = array(
            BorhanTubeMogulSyndicationFeedCategories::ARTS_AND_ANIMATION => 1,
            BorhanTubeMogulSyndicationFeedCategories::COMEDY => 3,
            BorhanTubeMogulSyndicationFeedCategories::ENTERTAINMENT => 4,
            BorhanTubeMogulSyndicationFeedCategories::MUSIC => 5,
            BorhanTubeMogulSyndicationFeedCategories::NEWS_AND_BLOGS => 6,
            BorhanTubeMogulSyndicationFeedCategories::SCIENCE_AND_TECHNOLOGY => 7,
            BorhanTubeMogulSyndicationFeedCategories::SPORTS => 8,
            BorhanTubeMogulSyndicationFeedCategories::TRAVEL_AND_PLACES => 9,
            BorhanTubeMogulSyndicationFeedCategories::VIDEO_GAMES => 10,
            BorhanTubeMogulSyndicationFeedCategories::ANIMALS_AND_PETS => 11,
            BorhanTubeMogulSyndicationFeedCategories::AUTOS => 12,
            BorhanTubeMogulSyndicationFeedCategories::VLOGS_PEOPLE => 13,
            BorhanTubeMogulSyndicationFeedCategories::HOW_TO_INSTRUCTIONAL_DIY => 14,
            BorhanTubeMogulSyndicationFeedCategories::COMMERCIALS_PROMOTIONAL => 15,
            BorhanTubeMogulSyndicationFeedCategories::FAMILY_AND_KIDS => 16,
        );
	public static function getCategoryId( $category )
	{
            return self::$mapCategories[$category];
	}
        
        public static function getCategoryName( $id )
        {
            $arrCategories = array_flip(self::$mapCategories);
            return $arrCategories[$id];
        }
}
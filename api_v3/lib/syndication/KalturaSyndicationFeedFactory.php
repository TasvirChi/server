<?php
/**
 * @package api
 * @subpackage objects.factory
 */
class BorhanSyndicationFeedFactory
{
	static function getInstanceByType ($type)
	{
		switch ($type) 
		{
			case BorhanSyndicationFeedType::GOOGLE_VIDEO:
				$obj = new BorhanGoogleVideoSyndicationFeed();
				break;
			case BorhanSyndicationFeedType::YAHOO:
				$obj = new BorhanYahooSyndicationFeed();
				break;
			case BorhanSyndicationFeedType::ITUNES:
				$obj = new BorhanITunesSyndicationFeed();
				break;
			case BorhanSyndicationFeedType::TUBE_MOGUL:
				$obj = new BorhanTubeMogulSyndicationFeed();
				break;
			case BorhanSyndicationFeedType::BORHAN:
				$obj = new BorhanGenericSyndicationFeed();
				break;
			case BorhanSyndicationFeedType::BORHAN_XSLT:
				$obj = new BorhanGenericXsltSyndicationFeed();
				break;
			default:
				$obj = new BorhanBaseSyndicationFeed();
				break;
		}
		
		return $obj;
	}
	
	static function getRendererByType($type)
	{
		switch ($type)
		{
			case BorhanSyndicationFeedType::GOOGLE_VIDEO:
				$obj = new GoogleVideoFeedRenderer();
				break;
			case BorhanSyndicationFeedType::YAHOO:
				$obj = new YahooFeedRenderer();
				break;
			case BorhanSyndicationFeedType::ITUNES:
				$obj = new ITunesFeedRenderer();
				break;
			case BorhanSyndicationFeedType::TUBE_MOGUL:
				$obj = new TubeMogulFeedRenderer();
				break;
			case BorhanSyndicationFeedType::BORHAN:
			case BorhanSyndicationFeedType::BORHAN_XSLT:
			default:
				return new BorhanFeedRenderer();
				break;
		}
		return $obj;
	}
}

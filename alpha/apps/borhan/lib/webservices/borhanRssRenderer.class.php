<?php
class borhanRssRenderer
{
	const TYPE_YAHOO = 1;
	const TYPE_TABOOLA = 2;
	
	public function __construct ( $type = self::TYPE_YAHOO )
	{
		$this->type = $type;
	}
	
	public function startMrss ( )
	{
		if ( $this->type == self::TYPE_YAHOO )
			return '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:borhan="http://borhan.com/playlist/1.0" >';
		if ( $this->type == self::TYPE_TABOOLA )
			return '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:borhan="http://borhan.com/playlist/1.0" xmlns:tv="http://taboola.com/schema/taboolavideo/1.0">';
			
	}

	public function endMrss ( )
	{
		return '</rss>';
	}
	
	// see http://search.yahoo.com/mrss
	// will create a good mRSS output for an entry
/*
 * 
 <media:content 
               url="http://www.foo.com/movie.mov" 
               fileSize="12216320" 
               type="video/quicktime"
               medium="video"
               isDefault="true" 
               expression="full" 
               bitrate="128" 
               framerate="25"
               samplingrate="44.1"
               channels="2"
               duration="185" 
               height="200"
               width="300" 
               lang="en" />
 */	

// TODO - add width & height after fixinf entry->getWidth mechanism 
	public function renderEntry ( $entry )
	{
		if  ( ! $entry instanceof  entry )
			return "";
		
		$entry_id = $entry->getId();
		
		$borhan_elements =
			"<borhan:entryId>" . $entry->getId() . "</borhan:entryId>";
		
		if (isset(kCurrentContext::$partner_id) &&
			!PermissionPeer::isValidForPartner(PermissionName::FEATURE_HIDE_SENSITIVE_DATA_IN_RSS_FEED, kCurrentContext::$partner_id))
		{
			$borhan_elements .=
				"<borhan:views>" . ($entry->getViews() ? $entry->getViews() : "0"). "</borhan:views>" .  
				"<borhan:plays>" . ($entry->getPlays() ? $entry->getPlays() : "0"). "</borhan:plays>" .
				"<borhan:userScreenName>" . kString::xmlEncode ($entry->getUserScreenName()) . "</borhan:userScreenName>" . 
				"<borhan:puserId>" . $entry->getPuserId() . "</borhan:puserId>" .
				"<borhan:userLandingPage>" . $entry->getUserLandingPage() . "</borhan:userLandingPage>";
		}
		else
		{
			$borhan_elements .=
				"<borhan:views>0</borhan:views>" .  
				"<borhan:plays>0</borhan:plays>" .
				"<borhan:userScreenName></borhan:userScreenName>" . 
				"<borhan:puserId></borhan:puserId>" .
				"<borhan:userLandingPage></borhan:userLandingPage>";
		}
		
		$borhan_elements .=
			"<borhan:partnerLandingPage>" . $entry->getPartnerLandingPage() . "</borhan:partnerLandingPage>" .
			"<borhan:tags>" . kString::xmlEncode ($entry->getTags()) . "</borhan:tags>" .
			"<borhan:adminTags>" . kString::xmlEncode ($entry->getAdminTags()) . "</borhan:adminTags>" .
			"<borhan:votes>" . ($entry->getVotes() ? $entry->getVotes() : "0") . "</borhan:votes>" .
			"<borhan:rank>" . ($entry->getRank() ? $entry->getRank() : "0") . "</borhan:rank>" .	
			"<borhan:createdAt>" . $entry->getCreatedAt() . "</borhan:createdAt>" .
			"<borhan:createdAtInt>" . $entry->getCreatedAt(null) . "</borhan:createdAtInt>" .
			"<borhan:sourceLink>" . $entry->getSourceLink() . "</borhan:sourceLink>" .
			"<borhan:credit>" . $entry->getCredit() . "</borhan:credit>" ;
		
		
		if ( $this->type == self::TYPE_TABOOLA )
		{			
			// TODO - use entry->getDisplayScope();
			$taboola_elements = $entry->getDisplayInSearch() >= 2 ? 
				"<tv:label>_KN_</tv:label>" .
				"<tv:uploader>" . $entry->getPartnerId() . "</tv:uploader>" 
				: '';
		}
		else
		{
			$taboola_elements = "";
		}
		
		// for now the partner_id & entry_id are set in the guid elementy of the item..
		// TODO - move the partner_id to be part of the primary key of the entry so entry will not appear in wrong partners
		 $mrss = '<item>' . 
		 	'<description>Borhan Item</description>' . 
		 	'<guid isPermaLink="false">' . $entry->getPartnerId() . "|" . $entry_id . '</guid>' . 
		 	'<link>' . $entry->getPartnerLandingPage()  . '</link>'.
		 	'<pubDate>' . $entry->getCreatedAt() . '</pubDate>' . 
		 	'<media:content ' . 
               'url="' . $entry->getDataUrl() . '/ext/flv" ' .  
		 		( $entry->getMediaType() == entry::ENTRY_MEDIA_TYPE_VIDEO ? 'type="video/x-flv" ' : '  ' ) . 
               'medium="' . $entry->getTypeAsString() . '" ' . 
//               'isDefault="true" 
//               'expression="full" 
//               'bitrate="128" ' .  
//              'framerate="25" ' . 
//               'samplingrate="44.1" ' . 
//              'channels="2" ' . 
               	'duration="' . (int)( $entry->getLengthInMsecs() / 1000 ) . '" ' . 
//               	'height="' . $entry->getHeight() . '" ' .
//              	'width="' . $entry->getWidth() . '" ' .  
               	'lang="en"' .  
               	'/> '.
               	'<media:title type="plain">' .  kString::xmlEncode ( $entry->getName()) . "</media:title>" .
               	'<media:description>'. kString::xmlEncode ( $entry->getDescription() ) . '</media:description>'.
               	'<media:keywords>' . kString::xmlEncode ( $entry->getSearchText() ) . '</media:keywords>' .
               	'<media:thumbnail url="'. $entry->getThumbnailUrl() . '/width/640/height/480"/>' . 
               '<media:credit role="borhan partner">' . $entry->getPartnerId() . '</media:credit>' .
		 		$borhan_elements . 
               	$taboola_elements .
               '</item>';
		 
		 return $mrss; 
	}
	
	private function recursiveRenderMrssFeed ( $list , $depth )
	{
//echo __METHOD__ . ":[$depth] class:" . ( is_array ( $list ) ? "array" : get_class ( $list ) ) . "<br>" ;
		$str = "";
		if ( is_array ( $list ))
		{

//echo print_r ( $list , true ) . "<br><br>";
			if ( $depth <=  0 ) return "";
			foreach ( $list as $name => $element )
			{
				$str .= $this->recursiveRenderMrssFeed ( $element , $depth-1);
			}
		}
		else
		{
			if ( $list instanceof entryWrapper )
				$str .= $this->renderEntry( $list->getWrappedObj() );
			else
				$str .= $this->renderEntry( $list );
		}		
		return $str;
	}
	
	public function renderMrssFeed ( $list , $page=null  , $result_count=null )
	{
//print_r ( $list );		
		$str = $this->startMrss() ;
		$str .= "<channel>";
		$str .= "<description>Borhan's mRss" . 
			( $page ? ", page: {$page}" : "" ) . 
			( $result_count ? ", results: {$result_count}" : ""  ). 
			"</description>" .
			"<title>Borhan's mRss</title>" .
			"<link>" . kString::xmlEncode ( $_SERVER["REQUEST_URI"] ) . "</link>"	;
		
		$str .= $this->recursiveRenderMrssFeed ( $list , 3 );
		$str .= "</channel>" ;
		$str .= $this->endMrss() ;
		return $str;
	}
}
?>

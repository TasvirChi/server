<?php

class BorhanFeedRenderer extends SyndicationFeedRenderer{
	
	const ITEMS_PLACEHOLDER = 'ITEMS_PLACEHOLDER';
	
	protected $borhanXslt = null;
	protected $borhanXsltItem = null;
	
	public function init($syndicationFeed, $syndicationFeedDB, $mimeType) {
		parent::init($syndicationFeed, $syndicationFeedDB, $mimeType);
		
		$xslt = $syndicationFeedDB->getXslt();
		if (($syndicationFeedDB->getType() == syndicationFeedType::BORHAN_XSLT) && (!is_null($xslt))) {
			$this->borhanXslt = $this->createBorhanMrssXslt($xslt);
			$this->borhanXsltItem = $this->createBorhanItemXslt($xslt);
		}
	}
	
	public function shouldEnableCache() {
		$mrssParams = $this->syndicationFeedDB->getMrssParameters();
		if ($mrssParams && $mrssParams->getItemXpathsToExtend()) 
			return false;
		
		return true;
	}

	public function handleHeader() {
		
		$mrss = $this->getBorhanMrssXml($this->syndicationFeed->name, $this->syndicationFeed->feedLandingPage, $this->syndicationFeed->feedDescription);
		if($this->borhanXslt)
			$mrss = kXml::transformXmlUsingXslt($mrss, $this->borhanXslt);
		
		$divideHeaderFromFooter = strpos($mrss, self::ITEMS_PLACEHOLDER);
		$mrss = substr($mrss,0,$divideHeaderFromFooter);
		
		$addXmlHeader =  $this->syndicationFeedDB->getAddXmlHeader();
			
		if (is_null($addXmlHeader) || $addXmlHeader == false)
			$mrss = $this->removeXmlHeader($mrss);
		
		return $mrss;
	}

	public function handleBody($entry, $e = null, $flavorAssetUrl = null) {
		$entryMrss =  $this->getMrssEntryXml($entry, $this->syndicationFeedDB,  $this->syndicationFeed->landingPage);
		
		if(!$entryMrss) {
			BorhanLog::err("No MRSS returned for entry [".$entry->getId()."]");
			return null;
		}
		
		return $entryMrss;
	}
	
	public function finalize($entryMrss, $moreItems) {
		if ($this->borhanXsltItem)
		{
			//syndication parameters to pass to XSLT
			$xslParams = array();
			$xslParams[XsltParameterName::BORHAN_HAS_NEXT_ITEM] = $moreItems;
			$xslParams[XsltParameterName::BORHAN_SYNDICATION_FEED_FLAVOR_PARAM_ID] = $this->syndicationFeedDB->getFlavorParamId();
				
			$entryMrss = kXml::transformXmlUsingXslt($entryMrss, $this->borhanXsltItem, $xslParams);
			$entryMrss = $this->removeNamespaces($entryMrss);
		}
		$entryMrss = $this->removeXmlHeader($entryMrss);
		return $entryMrss;
	}
	
	public function handleFooter() {
		$mrss = $this->getBorhanMrssXml($this->syndicationFeed->name, $this->syndicationFeed->feedLandingPage, $this->syndicationFeed->feedDescription);
	
		if($this->borhanXslt)
			$mrss = kXml::transformXmlUsingXslt($mrss, $this->borhanXslt);
	
		$divideHeaderFromFooter = strpos($mrss, self::ITEMS_PLACEHOLDER) + strlen(self::ITEMS_PLACEHOLDER);
		$mrss = substr($mrss,$divideHeaderFromFooter);
	
		return $mrss;
	}

	private function getBorhanMrssXml($title, $link = null, $description = null)
	{
		$mrss = kMrssManager::getMrssXml($title, $link, $description);
	
		foreach ($mrss->children() as $second_gen) {
			if ($second_gen->getName() == 'channel')
				$second_gen->addChild('items', self::ITEMS_PLACEHOLDER);
		}
	
		return $mrss->asXML();
	}
	
	/**
	 * return xlts with item place holder only when given xslt compatible with borhan feed
	 * @param string $xslt
	 * @return string $xslt
	 */
	private function createBorhanMrssXslt($xslt)
	{
		$xsl = new DOMDocument();
		if(!@$xsl->loadXML($xslt))
		{
			BorhanLog::err("Could not load xslt");
			return null;
		}
	
		$xpath = new DOMXPath($xsl);
	
		//remove items template
		$xslStylesheet = $xpath->query("//xsl:stylesheet");
		$item = $xpath->query("//xsl:template[@name='item']");
		$item->item(0)->parentNode->removeChild($item->item(0));
	
		//add place holder for items
		$items = $xpath->query("//xsl:apply-templates[@name='item']");
		$itemPlaceHolderNode = $xsl->createTextNode(self::ITEMS_PLACEHOLDER);
		$items->item(0)->parentNode->replaceChild($itemPlaceHolderNode,$items->item(0));
	
		return $xsl->saveXML();
	}
	
	/**
	 * return xlts with item template only when given xslt compatible with borhan feed
	 * @param string $xslt
	 * @return string $xslt
	 */
	private function createBorhanItemXslt($xslt)
	{
		$xsl = new DOMDocument();
		if(!@$xsl->loadXML($xslt))
		{
			BorhanLog::err("Could not load xslt");
			return null;
		}
	
		$xpath = new DOMXPath($xsl);
		$xslStylesheet = $xpath->query("//xsl:stylesheet");
		$rss = $xpath->query("//xsl:template[@name='rss']");
		$xslStylesheet->item(0)->removeChild($rss->item(0));
	
		return $xsl->saveXML();
	}
	
	/**
	 * @param $entry
	 * @param $syndicationFeed
	 * @return string
	 */
	private function getMrssEntryXml(entry $entry, syndicationFeed $syndicationFeed = null, $link = null)
	{
		if ($syndicationFeed->getMrssParameters())
			$mrssParams = clone $syndicationFeed->getMrssParameters();
		else
			$mrssParams = new kMrssParameters;
		$mrssParams->setLink($link);
		$mrssParams->setFilterByFlavorParams($syndicationFeed->getFlavorParamId());
		$mrssParams->setIncludePlayerTag(true);
		$mrssParams->setPlayerUiconfId($syndicationFeed->getPlayerUiconfId());
		$mrssParams->setStorageId($syndicationFeed->getStorageId());
		$mrssParams->setServePlayManifest($syndicationFeed->getServePlayManifest());
		$mrssParams->setPlayManifestClientTag('feed:' . $syndicationFeed->getId());

		$features = null;
		if ($syndicationFeed->getUseCategoryEntries())
		{
			BorhanLog::info("Getting entry's associated categories from the category_entry table");
			$features = array (ObjectFeatureType::CATEGORY_ENTRIES);
		}
		
		$mrss = kMrssManager::getEntryMrssXml($entry, null, $mrssParams, $features);
	
		if(!$mrss)
		{
			BorhanLog::err("No MRSS returned for entry [".$entry->getId()."]");
			return null;
		}
	
		return $mrss->asXML();
	}
	
	/**
	 * @param string $mrss
	 * @return string
	 */
	private function removeXmlHeader($mrss)
	{
		$position = strpos($mrss,'<?xml version="1.0"?>');
		if($position !== false){
			$divideHeaderFromFooter = $position + strlen('<?xml version="1.0"?>') + 1;
			$mrss = substr($mrss,$divideHeaderFromFooter);
		}
	
		$position = strpos($mrss,'<?xml version="1.0" encoding="UTF-8"?>');
		if($position !== false){
			$divideHeaderFromFooter = $position + strlen('<?xml version="1.0" encoding="UTF-8"?>') + 1;
			$mrss = substr($mrss,$divideHeaderFromFooter);
		}
	
		return $mrss;
	}
	
	/**
	 *
	 * @param stinr $xmlStr
	 * @return string
	 */
	private function removeNamespaces($xmlStr)
	{
		//	return preg_replace("/<.*(xmlns *= *[\"'].[^\"']*[\"']).[^>]*>/i", "", $xmlStr);
		//return preg_replace("/ xmlns:[a-zA-Z0-9_]{1,}=[\"'].[^\"']*[\"']/", "", $xmlStr);
		return preg_replace("/ xmlns:[^= ]{1,}=[\"][^\"]*[\"]/i", "", $xmlStr);
	}
}


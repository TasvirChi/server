<?php
/**
 * @package plugins.metroPcsDistribution
 * @subpackage lib
 */
class MetroPcsDistributionFeedHelper
{
	/**
	 * @var DOMDocument
	 */
	protected $doc;
	
	/**
	 * @var DOMXPath
	 */
	protected $xpath;
	
	/**
	 * @var AttUverseDistributionProfile
	 */
	protected $distributionProfile;

	/**
	 * @var BorhanEntryDistribution
	 */
	protected $entryDistribution;
	
	/**
	 * @var BorhanMetroPcsDistributionJobProviderData
	 */
	protected $providerData;
	
	/**
	 * @var array
	 */
	protected $fieldValues;
		
	
	/**
	 * @param $templateName
	 * @param $distributionProfile
	 */
	public function __construct($templateName, $entryDistribution, BorhanMetroPcsDistributionProfile $distributionProfile, BorhanMetroPcsDistributionJobProviderData $providerData) 
	{
		$this->entryDistribution = $entryDistribution;
		$this->distributionProfile = $distributionProfile;
		$this->providerData = $providerData;
		$this->fieldValues = unserialize($providerData->fieldValues);
		if (!$this->fieldValues) {
		    $this->fieldValues = array();
		}
		$xmlTemplate = realpath(dirname(__FILE__) . '/../') . '/xml_templates/' . $templateName;
		$this->doc = new KDOMDocument();
		$this->doc->load($xmlTemplate);		
		$this->xpath = new DOMXPath($this->doc);
		$this->xpath->registerNamespace('msdp', 'http://www.real.com/msdp');	
		
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:title', $this->getValueForField(BorhanMetroPcsDistributionField::TITLE));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:link', $this->getValueForField(BorhanMetroPcsDistributionField::LINK));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:externalId', $this->getValueForField(BorhanMetroPcsDistributionField::EXTERNAL_ID));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:providerId', $this->getValueForField(BorhanMetroPcsDistributionField::PROVIDER_ID));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:shortDescription', $this->getValueForField(BorhanMetroPcsDistributionField::SHORT_DESCRIPTION));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:description', $this->getValueForField(BorhanMetroPcsDistributionField::DESCRIPTION));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:language', $this->getValueForField(BorhanMetroPcsDistributionField::LANGUAGE));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:copyright', $this->getValueForField(BorhanMetroPcsDistributionField::COPYRIGHT));		
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:managingEditor', $this->getValueForField(BorhanMetroPcsDistributionField::MANAGING_EDITOR));
		
		$pubDate = $this->getValueForField(BorhanMetroPcsDistributionField::PUB_DATE);
		if ($pubDate) 
		{
		   kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:pubDate',date('D M j G:i:s T Y', intval($pubDate))); 
		}
					
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:category', $this->getValueForField(BorhanMetroPcsDistributionField::CATEGORY));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:upc', $this->getValueForField(BorhanMetroPcsDistributionField::UPC));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:isrc', $this->getValueForField(BorhanMetroPcsDistributionField::ISRC));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:program', $this->getValueForField(BorhanMetroPcsDistributionField::PROGRAM));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:seasonId', $this->getValueForField(BorhanMetroPcsDistributionField::SEASON_ID));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:episodicId', $this->getValueForField(BorhanMetroPcsDistributionField::EPISODIC_ID));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:chapterId', $this->getValueForField(BorhanMetroPcsDistributionField::CHAPTER_ID));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:artist', $this->getValueForField(BorhanMetroPcsDistributionField::ARTIST));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:performer', $this->getValueForField(BorhanMetroPcsDistributionField::PERFORMER));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:director', $this->getValueForField(BorhanMetroPcsDistributionField::DIRECTOR));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:studio', $this->getValueForField(BorhanMetroPcsDistributionField::STUDIO));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:originalRelease', $this->getValueForField(BorhanMetroPcsDistributionField::ORIGINAL_RELEASE));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:topStory', $this->getValueForField(BorhanMetroPcsDistributionField::TOP_STORY));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:sortOrder', $this->getValueForField(BorhanMetroPcsDistributionField::SORT_ORDER));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:sortName', $this->getValueForField(BorhanMetroPcsDistributionField::SORT_NAME));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:genre', $this->getValueForField(BorhanMetroPcsDistributionField::GENRE));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:keywords', $this->getValueForField(BorhanMetroPcsDistributionField::KEYWORDS));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:localCode', $this->getValueForField(BorhanMetroPcsDistributionField::LOCAL_CODE));
		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:entitlements', $this->getValueForField(BorhanMetroPcsDistributionField::ENTITLEMENTS));
				
		$startDate = new DateTime('@'.$this->getValueForField(BorhanMetroPcsDistributionField::START_DATE));
		if ($startDate) 
		{	
			// force time zone to EST
			$startDate->setTimezone(new DateTimeZone('EST'));
			$date = $startDate->format('c');			
		    kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:startDate',$date);  
		}		
		
		$endDate = new DateTime('@'.$this->getValueForField(BorhanMetroPcsDistributionField::END_DATE));
		if ($endDate) 
		{
			// force time zone to EST
			$endDate->setTimezone(new DateTimeZone('EST'));
		    $date = $endDate->format('c');			
		    kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:endDate',$date); 
		}	

		kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:rating', $this->getValueForField(BorhanMetroPcsDistributionField::RATING));
	}
				
	/**
	 * @param string $xpath
	 * @param string $value
	 * @param DOMNode $contextnode
	 */
	public function setNodeValue($xpath, $value, DOMNode $contextnode = null)
	{		
		if ($contextnode)
			$node = $this->xpath->query($xpath, $contextnode)->item(0);
		else 
			$node = $this->xpath->query($xpath)->item(0);
		if (!is_null($node))
		{
			// if CDATA inside, set the value of CDATA
			if ($node->childNodes->length > 0 && $node->childNodes->item(0)->nodeType == XML_CDATA_SECTION_NODE)
				$node->childNodes->item(0)->nodeValue = $value;
			else
				$node->nodeValue = $value;
		}
	}
	
	private function getValueForField($fieldName)
	{
	    if (isset($this->fieldValues[$fieldName])) {
	        return $this->fieldValues[$fieldName];
	    }
	    return null;
	}	
		
	/**
	 * set flavors in XML
	 * @param BorhanThumbAsset $thumbAssets
	 */
	public function setThumbnails($thumbAssets, $thumbUrls)
	{	
		$templateImageNode = $this->xpath->query('/msdp:rss/msdp:channel/msdp:image')->item(0);		
		if (count($thumbAssets) && count($thumbUrls))	
		{										
			foreach ($thumbAssets as $thumbAsset)
			{
				$url = $thumbUrls[$thumbAsset->id];
				kXml::setNodeValue($this->xpath,'msdp:url', $url, $templateImageNode);
				kXml::setNodeValue($this->xpath,'msdp:width', $thumbAsset->width , $templateImageNode);
				kXml::setNodeValue($this->xpath,'msdp:height', $thumbAsset->height , $templateImageNode);
				//$this->cloneNode instead the DOMNode cloneNode since the cloneNode doesn't deep copy the namespaces inside the tags
				$newImageNode = $this->cloneNode($templateImageNode, $this->doc);
				$templateImageNode->parentNode->insertBefore($newImageNode, $templateImageNode);
			}			
			$templateImageNode->parentNode->removeChild($templateImageNode);		
		}
		else
		{
			//ignore image element	
			kXml::setNodeValue($this->xpath,'@ignore', "Y", $templateImageNode);
		}
	}
	
	/**
	 * set flavors in XML
	 * @param BorhanFlavorAsset $flavorAssets
	 */
	public function setFlavor ($flavorAsset, $entryDuration, $currenTime)
	{	
		$templateItemNode = $this->xpath->query('/msdp:rss/msdp:channel/msdp:item')->item(0);
		if($flavorAsset)
		{	
			$itemTitle = $this->getValueForField(BorhanMetroPcsDistributionField::ITEM_TITLE);
			$itemDescription= $this->getValueForField(BorhanMetroPcsDistributionField::ITEM_DESCRIPTION);
			$itemType= $this->getValueForField(BorhanMetroPcsDistributionField::ITEM_TYPE);
			//$url = $this->getAssetUrl($flavorAsset);
			$url = $this->flavorAssetUniqueName($flavorAsset, $currenTime);			
			kXml::setNodeValue($this->xpath,'msdp:title', $itemTitle, $templateItemNode);
			kXml::setNodeValue($this->xpath,'msdp:description', $itemDescription, $templateItemNode);
			kXml::setNodeValue($this->xpath,'msdp:type', $itemType, $templateItemNode);				
			kXml::setNodeValue($this->xpath,'msdp:width', $flavorAsset->width , $templateItemNode);		
			kXml::setNodeValue($this->xpath,'msdp:height', $flavorAsset->height , $templateItemNode);					
			kXml::setNodeValue($this->xpath,'msdp:enclosure/@url', $url, $templateItemNode);
			kXml::setNodeValue($this->xpath,'msdp:enclosure/@length', $entryDuration, $templateItemNode);
			//$this->cloneNode instead the DOMNode cloneNode since the cloneNode doesn't deep copy the namespaces inside the tags
			$newItemNode = $this->cloneNode($templateItemNode, $this->doc);
			$templateItemNode->parentNode->insertBefore($newItemNode, $templateItemNode);
			$templateItemNode->parentNode->removeChild($templateItemNode);
		}
		else
		{
			//ignore image element	
			kXml::setNodeValue($this->xpath,'@ignore', "Y", $templateItemNode);
		}
	}
	
	/**
	 * Setting the start and end dates to passed dates while maintaining startDate<endDate
	 */
	public function setTimesForDelete()
	{
		//two days ago
		$startDate = time() - 48*60*60;  
		$startDate = new DateTime('@'.$startDate);
		if ($startDate) 
		{	
			// force time zone to EST
			$startDate->setTimezone(new DateTimeZone('EST'));
			$date = $startDate->format('c');			
		    kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:startDate',$date);  
		}		
		
		//yesterday
		$endDate = time() - 24*60*60;  
		$endDate = new DateTime('@'.$endDate);
		if ($endDate) 
		{
			// force time zone to EST
			$endDate->setTimezone(new DateTimeZone('EST'));
		    $date = $endDate->format('c');			
		    kXml::setNodeValue($this->xpath,'/msdp:rss/msdp:channel/msdp:endDate',$date); 
		}	
	}
	
	public function setImageIgnore()
	{
		$imageNode = $this->xpath->query('/msdp:rss/msdp:channel/msdp:image')->item(0);	
		kXml::setNodeValue($this->xpath,'@ignore', "Y", $imageNode);		
	}
	
	
	public function setItemIgnore()
	{		
		$itemNode = $this->xpath->query('/msdp:rss/msdp:channel/msdp:item')->item(0);
		kXml::setNodeValue($this->xpath,'msdp:type', $this->getValueForField(BorhanMetroPcsDistributionField::ITEM_TYPE), $itemNode);
		kXml::setNodeValue($this->xpath,'@ignore', "Y", $itemNode);		
	}
	
	public function getXmlString()
	{
		return $this->doc->saveXML();
	}
		
	/**
	 * creates unique name for flavor asset
	 * @param BorhanFlavorAsset $flavorAsset
	 */
	public function flavorAssetUniqueName($flavorAsset, $currentTime)
	{
		$path = $this->distributionProfile->ftpPath;
		$fileExt = $flavorAsset->fileExt;	
		//$uniqueName = $path.'/'.$currentTime.'_'.$this->entryDistribution->id.'_'.$flavorAsset->entryId.'_'.$flavorAsset->id.'.'.$fileExt;
		$uniqueName = $currentTime.'_'.$this->entryDistribution->id.'_'.$flavorAsset->entryId.'_'.$flavorAsset->id.'.'.$fileExt;
		return $uniqueName;		
	}
	
	private function cloneNode($node,$doc)
	{
	    $nd = $doc->createElement($node->nodeName);           
	    foreach($node->attributes as $value)
	        $nd->setAttribute($value->nodeName,$value->value);
	           
	    if(!$node->childNodes)
	        return $nd;
	               
	    foreach($node->childNodes as $child) {
	        if($child->nodeName=="#text")
	            $nd->appendChild($doc->createTextNode($child->nodeValue));
	        else
	            $nd->appendChild($this->cloneNode($child,$doc));
	    }          
    	return $nd;
	}
	
}
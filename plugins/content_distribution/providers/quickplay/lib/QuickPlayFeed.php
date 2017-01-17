<?php
/**
 * @package plugins.quickPlayDistribution
 * @subpackage lib
 */
class QuickPlayFeed
{
	const TEMPLATE_XML = 'quickplay_template.xml';
	
	/**
	 * @var DOMDocument
	 */
	protected $_doc;
	
	/**
	 * @var DOMXPath
	 */
	protected $_xpath;
	
	/**
	 * @var BorhanDistributionJobData
	 */
	protected $_distributionJobData;
	
	/**
	 * @var BorhanQuickPlayDistributionProfile
	 */
	protected $_distributionProfile;
	
	/**
	 * @var BorhanQuickPlayDistributionJobProviderData
	 */
	protected $_providerData;
	
	/**
	 * @var array
	 */
	protected $_fieldValues;
	
	/**
	 * DOMNode
	 */
	protected $_enclosureNode;
	
	/**
	 * @var array
	 */
	protected $_enclosuresXmls;
	
	/**
	 * @param string $templateName
	 * @param BorhanQuickPlayDistributionProfile $distributionProfile
	 * @param BorhanQuickPlayDistributionJobProviderData $providerData
	 */
	public function __construct(BorhanDistributionJobData $distributionJobData, BorhanQuickPlayDistributionJobProviderData $providerData, array $flavorAssets, array $thumbnailAssets, entry $entry)
	{
		$this->_distributionJobData = $distributionJobData;
		$this->_distributionProfile = $distributionJobData->distributionProfile;
		$this->_providerData = $providerData;
		$xmlTemplate = realpath(dirname(__FILE__) . '/../') . '/xml/' . self::TEMPLATE_XML;
		$this->_doc = new KDOMDocument();
		$this->_doc->load($xmlTemplate);
		$this->_xpath = new DOMXPath($this->_doc);
		$this->_xpath->registerNamespace('qpm', 'http://www.quickplaymedia.com');
		
		// enclosure node template
		$node = $this->_xpath->query('//qpm:enclosure', $this->_doc->firstChild)->item(0);
		$this->_enclosureNode = $node->cloneNode(true);
		$node->parentNode->removeChild($node);
		
		$this->_fieldValues = unserialize($this->_providerData->fieldValues);
		if (!$this->_fieldValues) 
			$this->_fieldValues = array();
		
		kXml::setNodeValue($this->_xpath,'/rss/channel/title', $this->_distributionProfile->channelTitle);
		kXml::setNodeValue($this->_xpath,'/rss/channel/link', $this->_distributionProfile->channelLink);
		kXml::setNodeValue($this->_xpath,'/rss/channel/description', $this->_distributionProfile->channelDescription);
		kXml::setNodeValue($this->_xpath,'/rss/channel/managingEditor', $this->_distributionProfile->channelManagingEditor);
		kXml::setNodeValue($this->_xpath,'/rss/channel/language', $this->_distributionProfile->channelLanguage);
		kXml::setNodeValue($this->_xpath,'/rss/channel/image/title', $this->_distributionProfile->channelImageTitle);
		kXml::setNodeValue($this->_xpath,'/rss/channel/image/width', $this->_distributionProfile->channelImageWidth);
		kXml::setNodeValue($this->_xpath,'/rss/channel/image/height', $this->_distributionProfile->channelImageHeight);
		kXml::setNodeValue($this->_xpath,'/rss/channel/image/link', $this->_distributionProfile->channelImageLink);
		kXml::setNodeValue($this->_xpath,'/rss/channel/image/url', $this->_distributionProfile->channelImageUrl);
		
		kXml::setNodeValue($this->_xpath,'/rss/channel/copyright', $this->_distributionProfile->channelCopyright);
		$this->setNodeValueDateFieldConfigId('/rss/channel/pubDate', BorhanQuickPlayDistributionField::PUB_DATE);
		$this->setNodeValueDate('/rss/channel/lastBuildDate', time());
		kXml::setNodeValue($this->_xpath,'/rss/channel/generator', $this->_distributionProfile->channelGenerator);
		kXml::setNodeValue($this->_xpath,'/rss/channel/rating', $this->_distributionProfile->channelRating);
		kXml::setNodeValue($this->_xpath,'/rss/channel/language', $this->_distributionProfile->channelLanguage);
		

		$this->setNodeValueFieldConfigId('/rss/channel/item/title', BorhanQuickPlayDistributionField::TITLE);
		$this->setNodeValueFieldConfigId('/rss/channel/item/description', BorhanQuickPlayDistributionField::DESCRIPTION);
		$this->setNodeValueFieldConfigId('/rss/channel/item/guid', BorhanQuickPlayDistributionField::GUID);
		$this->setNodeValueFieldConfigId('/rss/channel/item/category', BorhanQuickPlayDistributionField::CATEGORY);
		$this->setNodeValueDateFieldConfigId('/rss/channel/item/pubDate', BorhanQuickPlayDistributionField::PUB_DATE);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:keywords', BorhanQuickPlayDistributionField::QPM_KEYWORDS);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:priceID', BorhanQuickPlayDistributionField::QPM_PRICE_ID);
		$this->setNodeValueDateFieldConfigId('/rss/channel/item/qpm:updateDate', BorhanQuickPlayDistributionField::QPM_UPDATE_DATE);
		$this->setNodeValueDateFieldConfigId('/rss/channel/item/qpm:expiryDate', BorhanQuickPlayDistributionField::QPM_EXPIRY_DATE);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:sortOrder', BorhanQuickPlayDistributionField::QPM_SORT_ORDER);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:genre', BorhanQuickPlayDistributionField::QPM_GENRE);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:copyright', BorhanQuickPlayDistributionField::QPM_COPYRIGHT);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:artist', BorhanQuickPlayDistributionField::QPM_ARTIST);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:director', BorhanQuickPlayDistributionField::QPM_DIRECTOR);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:producer', BorhanQuickPlayDistributionField::QPM_PRODUCER);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:expDatePadding', BorhanQuickPlayDistributionField::QPM_EXP_DATE_PADDING);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:onDeviceExpirationPadding', BorhanQuickPlayDistributionField::QPM_ON_DEVICE_EXPIRATION_PADDING);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:onDeviceExpiration', BorhanQuickPlayDistributionField::QPM_ON_DEVICE_EXPIRATION);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:groupCategory', BorhanQuickPlayDistributionField::QPM_GROUP_CATEGORY);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:notes', BorhanQuickPlayDistributionField::QPM_NOTES);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:rating/@scheme', BorhanQuickPlayDistributionField::QPM_RATING_SCHEMA);
		$this->setNodeValueFieldConfigId('/rss/channel/item/qpm:rating/@value', BorhanQuickPlayDistributionField::QPM_RATING);

		$this->removeNodeIfEmpty('/rss/channel/generator');
		$this->removeNodeIfEmpty('/rss/channel/rating');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:artist');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:director');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:producer');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:expDatePadding');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:onDeviceExpirationPadding');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:onDeviceExpiration');
		$this->removeNodeIfEmpty('/rss/channel/item/qpm:groupCategory');

		foreach($thumbnailAssets as $thumbnailAsset)
		{
			$encodingProfile = $thumbnailAsset->getWidth().'x'.$thumbnailAsset->getHeight();
			$this->_enclosuresXmls[] =  
				$this->createEnclosureXml(
					$thumbnailAsset,
					'thumbnail',
					$encodingProfile,
					'0'
				);
		}
		
		foreach($flavorAssets as $flavorAsset)
		{
			if ($flavorAsset->getFlavorParams())
				$encodingProfile = $flavorAsset->getFlavorParams()->getName();
			else 
				$encodingProfile = 'Unknown';
			$this->_enclosuresXmls[] =
				$this->createEnclosureXml(
					$flavorAsset,
					'content',
					$encodingProfile,
					round($entry->getDuration())
				);
		}
	}
	
	/**
	 * @param string $xpath
	 * @param string $fieldConfigId
	 */
	public function setNodeValueFieldConfigId($xpath, $fieldConfigId, DOMNode $contextnode = null)
	{
		if (isset($this->_fieldValues[$fieldConfigId]))
			kXml::setNodeValue($this->_xpath,$xpath, $this->_fieldValues[$fieldConfigId], $contextnode);
	}
	
	/**
	 * @param string $xpath
	 * @param string $fieldConfigId
	 */
	protected function setNodeValueDateFieldConfigId($xpath, $fieldConfigId, DOMNode $contextnode = null)
	{
		if (isset($this->_fieldValues[$fieldConfigId]) && $this->_fieldValues[$fieldConfigId]) 
		{
			$this->setNodeValueDate($xpath, $this->_fieldValues[$fieldConfigId], $contextnode);
		}
	}
	
	/**
	 * @param string $xpath
	 * @param string $value
	 * @param DOMNode $contextnode
	 */
	public function setNodeValue($xpath, $value, DOMNode $contextnode = null)
	{
		if ($contextnode)
			$node = $this->_xpath->query($xpath, $contextnode)->item(0);
		else 
			$node = $this->_xpath->query($xpath)->item(0);
		if (!is_null($node))
		{
			// if CDATA inside, set the value of CDATA
			if ($node->childNodes->length > 0 && $node->childNodes->item(0)->nodeType == XML_CDATA_SECTION_NODE)
				$node->childNodes->item(0)->nodeValue = $value;
			else
				$node->nodeValue = $value;
		}
	}
	
	public function setNodeValueDate($xpath, $value, DOMNode $contextnode = null)
	{
		$dateTime = new DateTime('@'.$value);
		// force time zone to GMT
		$dateTime->setTimezone(new DateTimeZone('GMT'));
		$date = $dateTime->format('r');
		kXml::setNodeValue($this->_xpath,$xpath, $date, $contextnode);
	}

	public function removeNodeIfEmpty($xpath)
	{
		$node = $this->_xpath->query($xpath)->item(0);
		if (is_null($node))
			return;

		if ($node->nodeValue === '')
			$node->parentNode->removeChild($node);
	}
	
	/**
	 * @param string $xpath
	 * @param DOMNode $element
	 */
	public function appendElement($xpath, DOMNode $element)
	{
		$parentElement = $this->_xpath->query($xpath)->item(0);
		if ($parentElement && $parentElement instanceof DOMNode)
		{
			$parentElement->appendChild($element);
		}
	}
	
	/**
	 * @param string $xpath
	 */
	public function getNodeValue($xpath)
	{
		$node = $this->_xpath->query($xpath)->item(0);
		if (!is_null($node))
			return $node->nodeValue;
		else
			return null;
	}
	
	public function getXml()
	{
		$xml = $this->_doc->saveXML();
		$xml = str_replace('<enclosurePlaceholder/>', implode("\n", $this->_enclosuresXmls), $xml);
		return $xml;
	}
	
	protected function getContentTypeFromUrl($url)
	{
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_HEADER, true);
		curl_setopt($this->ch, CURLOPT_NOBODY, true);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		$headers = curl_exec($this->ch);
		if (preg_match('/Content-Type: ([^;]*)/', $headers, $matched))
		{
			return trim($matched[1]);
		}
		else
		{
			BorhanLog::alert('"Content-Type" header was not found for the following URL: '. $url);
			return null;
		}
	}
	
	public function getAssetUrl(asset $asset)
	{
		$urlManager = DeliveryProfilePeer::getDeliveryProfile($asset->getEntryId());
		if($asset instanceof flavorAsset)
			$urlManager->initDeliveryDynamicAttributes(null, $asset);
		$url = $urlManager->getFullAssetUrl($asset);
		$url = preg_replace('/^https?:\/\//', '', $url);
		return 'http://' . $url;
	}
	
	/**
	 * @param asset $asset
	 * @param string $class
	 * @param string $encodingProfile
	 * @param string $duration
	 * @param string $url
	 */
	protected function createEnclosureXml(asset $asset, $class, $encodingProfile, $duration)
	{
		/**
		 * 
		 * In QuickPlay's XML example, the namespace "http://www.quickplaymedia.com" is added to the "enclosure" 
		 * element regardless to the fact that it was registerted with the prefix "qpm" on the root element.
		 * We cannot set a namespace that was already defined with a prefix because DOMDocument will add the element
		 * as "qpm:enclosure" and won't set the namespace explicitly.
		 * 
		 * The hack is to create a new KDOMDocument with default namespace "http://www.quickplaymedia.com" and then
		 * add it to the xml manually (see getXml() method)
		 * 
		 */
		$syncKey = $asset->getSyncKey(flavorAsset::FILE_SYNC_FLAVOR_ASSET_SUB_TYPE_ASSET);
		$fileSync = kFileSyncUtils::getLocalFileSyncForKey($syncKey);
			
		$contentNode = $this->_enclosureNode->cloneNode(true);
		kXml::setNodeValue($this->_xpath,'@encodingProfile', $encodingProfile, $contentNode);
		$url = $this->getAssetUrl($asset);
		$mimeType = $this->getContentTypeFromUrl($url);
			
		$enclosureDoc = new KDOMDocument();
		$enclosureElement = $enclosureDoc->createElementNS('http://www.quickplaymedia.com', 'enclosure');
		$xmlElement = $enclosureDoc->createElement('xml');
		$enclosureDoc->appendChild($xmlElement);
		$enclosureNode = $enclosureDoc->importNode($contentNode, true);
		$enclosureNode->setAttribute('class', $class);
		$link = $enclosureNode->getElementsByTagName('link')->item(0);
		$link->setAttribute('type', $mimeType);
		$link->setAttribute('length', $fileSync->getFileSize());
		$link->setAttribute('duration', $duration);
		$link->setAttribute('url', pathinfo($fileSync->getFilePath(), PATHINFO_BASENAME));
		$xmlElement->appendChild($enclosureNode);
		return $enclosureDoc->saveXML($enclosureNode);
	}
}
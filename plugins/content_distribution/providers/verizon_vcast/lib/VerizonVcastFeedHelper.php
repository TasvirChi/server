<?php
/**
 * @package plugins.verizonVcastDistribution
 * @subpackage lib
 */
class VerizonVcastFeedHelper
{

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
	 * @var BorhanVerizonVcastDistributionProfile
	 */
	protected $_distributionProfile;
	
	/**
	 * @var BorhanVerizonVcastDistributionJobProviderData
	 */
	protected $_providerData;
	
	/**
	 * @var array
	 */
	protected $_fieldValues;
	
	/**
	 * DOMNode
	 */
	protected $_imageNode;
	
	/**
	 * DOMNode
	 */
	protected $_itemNode;
	
	/**
	 * @param string $templateName
	 * @param BorhanVerizonVcastDistributionProfile $distributionProfile
	 * @param BorhanVerizonVcastDistributionJobProviderData $providerData
	 */
	public function __construct($templateName, BorhanDistributionJobData $distributionJobData, BorhanVerizonVcastDistributionJobProviderData $providerData, array $flavorAssets, array $thumbnailAssets)
	{
		$this->_distributionJobData = $distributionJobData;
		$this->_distributionProfile = $distributionJobData->distributionProfile;
		$this->_providerData = $providerData;
		$xmlTemplate = realpath(dirname(__FILE__) . '/../') . '/xml/' . $templateName;
		$this->_doc = new KDOMDocument();
		$this->_doc->load($xmlTemplate);
		$this->_xpath = new DOMXPath($this->_doc);
		
		// image node template
		$node = $this->_xpath->query('//ns2:image')->item(0);
		$this->_imageNode = $node->cloneNode(true);
		$node->parentNode->removeChild($node);
		
		// item node template
		$node = $this->_xpath->query('//ns2:item')->item(0);
		$this->_itemNode = $node->cloneNode(true);
		$node->parentNode->removeChild($node);

		$this->_fieldValues = unserialize($this->_providerData->fieldValues);
		if (!$this->_fieldValues) 
			$this->_fieldValues = array();
		
		$this->setNodeValueFieldConfigId('//ns2:title', BorhanVerizonVcastDistributionField::TITLE);
		$this->setNodeValueFieldConfigId('//ns2:externalid', BorhanVerizonVcastDistributionField::EXTERNAL_ID);
		$this->setNodeValueFieldConfigId('//ns2:shortdescription', BorhanVerizonVcastDistributionField::SHORT_DESCRIPTION);
		$this->setNodeValueFieldConfigId('//ns2:description', BorhanVerizonVcastDistributionField::DESCRIPTION);
		$this->setNodeValueFieldConfigId('//ns2:keywords', BorhanVerizonVcastDistributionField::KEYWORDS);
		$this->setNodeValueShortDateFieldConfigId('//ns2:pubDate', BorhanVerizonVcastDistributionField::PUB_DATE);
		$this->setNodeValueFieldConfigId('//ns2:category', BorhanVerizonVcastDistributionField::CATEGORY);
		$this->setNodeValueFieldConfigId('//ns2:genre', BorhanVerizonVcastDistributionField::GENRE);
		$this->setNodeValueFieldConfigId('//ns2:rating', BorhanVerizonVcastDistributionField::RATING);
		$this->setNodeValueFieldConfigId('//ns2:copyright', BorhanVerizonVcastDistributionField::COPYRIGHT);
		$this->setNodeValueFieldConfigId('//ns2:entitlement', BorhanVerizonVcastDistributionField::ENTITLEMENT);
		
		$this->setNodeValueFullDateFieldConfigId('//ns2:liveDate', BorhanVerizonVcastDistributionField::LIVE_DATE);
		$this->setNodeValueFullDateFieldConfigId('//ns2:endDate', BorhanVerizonVcastDistributionField::END_DATE);
		$this->setNodeValueFieldConfigId('//ns2:priority', BorhanVerizonVcastDistributionField::PRIORITY);
		$this->setNodeValueFieldConfigId('//ns2:allowStreaming', BorhanVerizonVcastDistributionField::ALLOW_STREAMING);
		$this->setNodeValueFieldConfigId('//ns2:streamingPriceCode', BorhanVerizonVcastDistributionField::STREAMING_PRICE_CODE);
		$this->setNodeValueFieldConfigId('//ns2:allowDownload', BorhanVerizonVcastDistributionField::ALLOW_DOWNLOAD);
		$this->setNodeValueFieldConfigId('//ns2:downloadPriceCode', BorhanVerizonVcastDistributionField::DOWNLOAD_PRICE_CODE);
		$this->setNodeValueFieldConfigId('//ns2:provider', BorhanVerizonVcastDistributionField::PROVIDER);
		$this->setNodeValueFieldConfigId('//ns2:providerid', BorhanVerizonVcastDistributionField::PROVIDER_ID);
		$this->setOrRemoveNodeValueFieldConfigId('//ns2:alertCode', BorhanVerizonVcastDistributionField::ALERT_CODE);
		
		foreach($thumbnailAssets as $thumbnailAsset)
		{
			$imageNode = $this->_imageNode->cloneNode(true);
			$url = $this->getAssetUrl($thumbnailAsset);
			kXml::setNodeValue($this->_xpath,'ns2:url', $url, $imageNode);
			$priorityNode = $this->_xpath->query('//ns2:priority')->item(0);
			$channelNode = $this->_xpath->query('//ns2:channel')->item(0);
			$channelNode->insertBefore($imageNode, $priorityNode);
		}
		
		foreach($flavorAssets as $flavorAsset)
		{
			$itemNode = $this->_itemNode->cloneNode(true);
			$url = $this->getAssetUrl($flavorAsset);
			kXml::setNodeValue($this->_xpath,'ns2:enclosure/@url', $url, $itemNode);
			if ($this->shouldIngestFlavor($flavorAsset))
			{
				kXml::setNodeValue($this->_xpath,'ns2:encode', 'Y', $itemNode);
				kXml::setNodeValue($this->_xpath,'ns2:move', 'Y', $itemNode);
			}
			$channelNode = $this->_xpath->query('//ns2:channel')->item(0);
			$channelNode->appendChild($itemNode);
		}
	}
	

	protected function getAssetUrl(asset $asset)
	{
		$urlManager = DeliveryProfilePeer::getDeliveryProfile($asset->getEntryId());
		if($asset instanceof flavorAsset)
			$urlManager->initDeliveryDynamicAttributes(null, $asset);
		$url = $urlManager->getFullAssetUrl($asset);
		$url = preg_replace('/^https?:\/\//', '', $url);
		$url = 'http://' . $url . '/ext/' . $asset->getId() . '.' . $asset->getFileExt(); 
		return $url;
	}
	
	protected function shouldIngestFlavor(asset $flavorAsset)
	{
		// mediaFile array was not initialized meaning this is the first submit job
		if (!($this->_distributionJobData->mediaFiles instanceof BorhanDistributionRemoteMediaFileArray))
			return true;
		
		// find the mediaFile of our flavor
		$foundMediaFile = null;
		foreach($this->_distributionJobData->mediaFiles as $mediaFile)
		{
			if ($mediaFile->assetId == $flavorAsset->getId())
			{
				$foundMediaFile = $mediaFile;
				break;
			}
		}
		
		// this mediaFile was not sent yet
		if (is_null($foundMediaFile))
			return true;
			
		return ($foundMediaFile->version != $flavorAsset->getVersion());
	}
	
	/**
	 * @param string $xpath
	 * @param string $elementName
	 * @param string $fieldConfigId
	 */
	protected function createAndAppendByXPathFieldConfig($xpath, $elementName, $fieldConfigId)
	{
		if (isset($this->_fieldValues[$fieldConfigId]) && $this->_fieldValues[$fieldConfigId])
		{
			$this->createAndAppendByXPath($xpath, $elementName, $this->_fieldValues[$fieldConfigId]);
		}
	}
	
	/**
	 * @param string $xpath
	 * @param string $elementName
	 * @param string $value
	 */
	protected function createAndAppendByXPath($xpath, $elementName, $value)
	{
		$element = $this->_doc->createElement($elementName, $value);
		$this->appendElement($xpath, $element);
	}
	
	/**
	 * @param string $xpath
	 * @param string $elementName
	 * @param string $fieldConfigId
	 */
	protected function createAndAppendByXPathDate($xpath, $elementName, $fieldConfigId)
	{
		if (isset($this->_fieldValues[$fieldConfigId]) && $this->_fieldValues[$fieldConfigId])
		{
			$element = $this->_doc->createElement($elementName, date(DATE_ATOM, $this->_fieldValues[$fieldConfigId]));
			$this->appendElement($xpath, $element);
		}
	}
	
	protected function setNodeValueFullDateFieldConfigId($xpath, $fieldConfigId)
	{
		if (isset($this->_fieldValues[$fieldConfigId]) && $this->_fieldValues[$fieldConfigId]) 
		{
			$dateTime = new DateTime('@'.$this->_fieldValues[$fieldConfigId]);
			// force time zone to EST
			$dateTime->setTimezone(new DateTimeZone('EST'));
			$date = $dateTime->format('c');
			kXml::setNodeValue($this->_xpath,$xpath, $date);
		}
	}
	
	protected function setNodeValueShortDateFieldConfigId($xpath, $fieldConfigId)
	{
		if (isset($this->_fieldValues[$fieldConfigId]))
			kXml::setNodeValue($this->_xpath,$xpath, date('Y-m-d', $this->_fieldValues[$fieldConfigId]));
	}
	
	/**
	 * @param string $xpath
	 * @param string $fieldConfigId
	 */
	public function setNodeValueFieldConfigId($xpath, $fieldConfigId)
	{
		if (isset($this->_fieldValues[$fieldConfigId]))
			kXml::setNodeValue($this->_xpath,$xpath, $this->_fieldValues[$fieldConfigId]);
	}
	
	/**
	 * @param string $xpath
	 * @param string $fieldConfigId
	 */
	public function setOrRemoveNodeValueFieldConfigId($xpath, $fieldConfigId)
	{
		if (isset($this->_fieldValues[$fieldConfigId]) && $this->_fieldValues[$fieldConfigId])
		{
			kXml::setNodeValue($this->_xpath,$xpath, $this->_fieldValues[$fieldConfigId]);
		}
		else 
		{
			$node = $this->_xpath->query($xpath)->item(0);
			if ($node)
				$node->parentNode->removeChild($node);
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
		return $this->_doc->saveXML();
	}
}
<?php
/**
 * @package plugins.huluDistribution
 * @subpackage lib
 */
class HuluFeedHelper
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
	 * @var BorhanHuluDistributionProfile
	 */
	protected $_distributionProfile;
	
	/**
	 * @var BorhanHuluDistributionJobProviderData
	 */
	protected $_providerData;
	
	/**
	 * @var array
	 */
	protected $_fieldValues;
	
	/**
	 * @param string $templateName
	 * @param BorhanHuluDistributionProfile $distributionProfile
	 * @param BorhanHuluDistributionJobProviderData $providerData
	 */
	public function __construct($templateName, BorhanHuluDistributionProfile $distributionProfile, BorhanHuluDistributionJobProviderData $providerData)
	{
		$this->_distributionProfile = $distributionProfile;
		$this->_providerData = $providerData;
		$xmlTemplate = realpath(dirname(__FILE__) . '/../') . '/xml/' . $templateName;
		$this->_doc = new KDOMDocument();
		$this->_doc->load($xmlTemplate);
		$this->_xpath = new DOMXPath($this->_doc);

		$this->_fieldValues = unserialize($this->_providerData->fieldValues);
		if (!$this->_fieldValues) 
			$this->_fieldValues = array();
		
		// series
		$this->setNodeValueFieldConfigId('/content/metadata/series/title', BorhanHuluDistributionField::SERIES_TITLE);
		$this->setNodeValueFieldConfigId('/content/metadata/series/description', BorhanHuluDistributionField::SERIES_DESCRIPTION);
		$this->setNodeValueFieldConfigId('/content/metadata/series/primaryCategory', BorhanHuluDistributionField::SERIES_PRIMARY_CATEGORY);
		$additionalCategories = explode(',', $this->_fieldValues[BorhanHuluDistributionField::SERIES_ADDITIONAL_CATEGORIES]);
		foreach($additionalCategories as $additionalCategory)
			$this->createAndAppendByXPath('/content/metadata/series/additionalCategories', 'category', $additionalCategory);
		$this->setNodeValueFieldConfigId('/content/metadata/series/channel', BorhanHuluDistributionField::SERIES_CHANNEL);
		
		// season
		$this->setNodeValueFieldConfigId('/content/metadata/season/seasonNumber', BorhanHuluDistributionField::SEASON_NUMBER);
		$this->setNodeValueFieldConfigId('/content/metadata/season/seasonSynopsis', BorhanHuluDistributionField::SEASON_SYNOPSIS);
		$this->setNodeValueFieldConfigId('/content/metadata/season/tuneinInformation', BorhanHuluDistributionField::SEASON_TUNEIN_INFORMATION);
		
		// video
		$this->setNodeValueFieldConfigId('/content/metadata/video/mediaType', BorhanHuluDistributionField::VIDEO_MEDIA_TYPE);
		$this->setNodeValueFieldConfigId('/content/metadata/video/title', BorhanHuluDistributionField::VIDEO_TITLE);
		$this->setNodeValueFieldConfigId('/content/metadata/video/episodeNumber', BorhanHuluDistributionField::VIDEO_EPISODE_NUMBER);
		$this->setNodeValueFieldConfigId('/content/metadata/video/rating', BorhanHuluDistributionField::VIDEO_RATING);
		$this->setNodeValueFieldConfigId('/content/metadata/video/contentRatingReason', BorhanHuluDistributionField::VIDEO_CONTENT_RATING_REASON);
		$this->setNodeValueFieldConfigId('/content/metadata/video/description', BorhanHuluDistributionField::VIDEO_DESCRIPTION);
		$this->setNodeValueFieldConfigId('/content/metadata/video/fullDescription', BorhanHuluDistributionField::VIDEO_FULL_DESCRIPTION);
		$this->setNodeValueFieldConfigId('/content/metadata/video/copyright', BorhanHuluDistributionField::VIDEO_COPYRIGHT);
		$this->setNodeValueFieldConfigId('/content/metadata/video/keywords', BorhanHuluDistributionField::VIDEO_KEYWORDS);
		$this->setNodeValueFieldConfigId('/content/metadata/video/language', BorhanHuluDistributionField::VIDEO_LANGUAGE);
		$this->setNodeValueFieldConfigId('/content/metadata/video/programmingType', BorhanHuluDistributionField::VIDEO_PROGRAMMING_TYPE);
		$this->setNodeValueFieldConfigId('/content/metadata/video/externalId', BorhanHuluDistributionField::VIDEO_EXTERNAL_ID);
		
		$this->setNodeValueFullDateFieldConfigId('/content/metadata/video/availableDate', BorhanHuluDistributionField::VIDEO_AVAILABLE_DATE);
		$this->setNodeValueFullDateFieldConfigId('/content/metadata/video/expirationDate', BorhanHuluDistributionField::VIDEO_EXPIRATION_DATE);
		$this->setNodeValueShortDateFieldConfigId('/content/metadata/video/originalPremiereDate', BorhanHuluDistributionField::VIDEO_ORIGINAL_PREMIERE_DATE);
		
		$this->addFileNode('Mezzanine video', $this->_providerData->fileBaseName.'.'.pathinfo($this->_providerData->videoAssetFilePath, PATHINFO_EXTENSION));
		$this->addFileNode('Mezzanine thumbnail', $this->_providerData->fileBaseName.'.'.pathinfo($this->_providerData->thumbAssetFilePath, PATHINFO_EXTENSION));
		foreach ($providerData->captionLocalPaths as $captionFilePath){
			if(file_exists($captionFilePath->value)){
				$remoteCaptionFileName = $providerData->fileBaseName.'.'.pathinfo($captionFilePath->value, PATHINFO_EXTENSION);
				$this->addFileNode('Text',$remoteCaptionFileName);
			}
		}
		
		$this->setCuePoints($this->_providerData->cuePoints);
	}
	
	/**
	 * @param array $cuePoints
	 */
	protected function setCuePoints(array $cuePoints)
	{
		$segments = array();
		foreach($cuePoints as $cuePoint)
		{
			/* @var $cuePoint BorhanAdCuePoint */
			$seconds = floor($cuePoint->startTime / 1000);
			$time = new DateTime('@'.$seconds, new DateTimeZone('UTC'));
			$hours = $time->format('H');
			$minutes = $time->format('i');
			$seconds = $time->format('s');
			//$fps = 25; // assume video is 25 frames per second
			//$percentOf1000 = $cuePoint->startTime % 1000 / 1000;
			//$frames = floor($fps * $percentOf1000);
			//$frames = str_pad($frames, 2, '0');
			$frames = '00';
			$segments[] = $hours.':'.$minutes.':'.$seconds.';'.$frames;
		}
		
		kXml::setNodeValue($this->_xpath,'/content/metadata/video/segments', implode(',', $segments));
	}
	
	protected function addFileNode($type, $name)
	{
		$fileNode = $this->_doc->createElement('file');
		$fileTypeNode = $this->_doc->createElement('fileType', $type);
		$fileNameNode = $this->_doc->createElement('fileName', $name);
		
		$fileNode->appendChild($fileTypeNode);
		$fileNode->appendChild($fileNameNode);
		
		$this->appendElement('/content/files', $fileNode);
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
			$date = new DateTime('@'.$this->_fieldValues[$fieldConfigId], new DateTimeZone('UTC'));
			$date = str_replace('+0000', '', $date->format(DateTime::ISO8601)); 
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
				$node->nodeValue = htmlspecialchars($value);
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
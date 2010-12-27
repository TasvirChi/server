<?php
class HuluDistributionProvider implements IDistributionProvider
{
	/**
	 * @var HuluDistributionProvider
	 */
	protected static $instance;
	
	protected function __construct()
	{
		
	}
	
	/**
	 * @return HuluDistributionProvider
	 */
	public static function get()
	{
		if(!self::$instance)
			self::$instance = new HuluDistributionProvider();
			
		return self::$instance;
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionProvider::getType()
	 */
	public function getType()
	{
		return HuluDistributionProviderType::get()->coreValue(HuluDistributionProviderType::HULU);
	}
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Hulu';
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::isDeleteEnabled()
	 */
	public function isDeleteEnabled()
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::isUpdateEnabled()
	 */
	public function isUpdateEnabled()
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::isReportsEnabled()
	 */
	public function isReportsEnabled()
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::isScheduleUpdateEnabled()
	 */
	public function isScheduleUpdateEnabled()
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::useDeleteInsteadOfUpdate()
	 */
	public function useDeleteInsteadOfUpdate()
	{
		return false;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::getJobIntervalBeforeSunrise()
	 */
	public function getJobIntervalBeforeSunrise()
	{
		return 0;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::getJobIntervalBeforeSunset()
	 */
	public function getJobIntervalBeforeSunset()
	{
		return 0;
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::getUpdateRequiredEntryFields()
	 */
	public function getUpdateRequiredEntryFields()
	{
//		e.g.
//		maybe should be taken from local config or kConf
//		return array(entryPeer::NAME, entryPeer::DESCRIPTION);

		return array();
	}

	/* (non-PHPdoc)
	 * @see IDistributionProvider::getUpdateRequiredMetadataXPaths()
	 */
	public function getUpdateRequiredMetadataXPaths()
	{
//		e.g.
//		maybe should be taken from local config or kConf
//		return array(
//			"/*[local-name()='metadata']/*[local-name()='ShortDescription']",
//			"/*[local-name()='metadata']/*[local-name()='LongDescription']",
//		);
		
		return array();
	}
	
	/**
	 * @param string $entryId
	 * @param KalturaHuluDistributionJobProviderData $providerData
	 * @return string
	 */
	public static function generateDeleteXML($entryId, KalturaHuluDistributionJobProviderData $providerData)
	{
		$xml = self::generateXML($entryId, $providerData);
		if(!$xml)
		{
			KalturaLog::err("No XML returned for entry [$entryId]");
			return null;
		}
			
		return $xml->saveXML();
	}
	
	/**
	 * @param string $entryId
	 * @param KalturaHuluDistributionJobProviderData $providerData
	 * @return string
	 */
	public static function generateUpdateXML($entryId, KalturaHuluDistributionJobProviderData $providerData)
	{
		$xml = self::generateXML($entryId, $providerData);
		if(!$xml)
		{
			KalturaLog::err("No XML returned for entry [$entryId]");
			return null;
		}
	
		// change end time to 5 days from now (it's an Hulu hack)
		$fiveDaysFromNow = date('Y-m-d\TH:i:s\Z', time() + (5 * 24 * 60 * 60));
		
		$nodes = $xml->getElementsByTagName('activeEndDate');
		foreach($nodes as $node)
			$node->replaceChild($xml->createTextNode($fiveDaysFromNow), $node->firstChild);
		
		$nodes = $xml->getElementsByTagName('searchableEndDate');
		foreach($nodes as $node)
			$node->replaceChild($xml->createTextNode($fiveDaysFromNow), $node->firstChild);
			
		$nodes = $xml->getElementsByTagName('archiveEndDate');
		foreach($nodes as $node)
			$node->replaceChild($xml->createTextNode($fiveDaysFromNow), $node->firstChild);
		
		return $xml->saveXML();
	}
	
	/**
	 * @param string $entryId
	 * @param KalturaHuluDistributionJobProviderData $providerData
	 * @return string
	 */
	public static function generateSubmitXML($entryId, KalturaHuluDistributionJobProviderData $providerData)
	{
		$xml = self::generateXML($entryId, $providerData);
		if(!$xml)
		{
			KalturaLog::err("No XML returned for entry [$entryId]");
			return null;
		}
		
		return $xml->saveXML();
	}
	
	/**
	 * @param string $entryId
	 * @param KalturaHuluDistributionJobProviderData $providerData
	 * @return DOMDocument
	 */
	public static function generateXML($entryId, KalturaHuluDistributionJobProviderData $providerData)
	{
		$entry = entryPeer::retrieveByPKNoFilter($entryId);
		$mrss = kMrssManager::getEntryMrss($entry);
		if(!$mrss)
		{
			KalturaLog::err("No MRSS returned for entry [$entryId]");
			return null;
		}
			
		$xml = new DOMDocument();
		if(!$xml->loadXML($mrss))
		{
			KalturaLog::err("Could not load MRSS as XML for entry [$entryId]");
			return null;
		}
		
		$xslPath = realpath(dirname(__FILE__) . '/../') . '/xml/submit.xsl';
		if(!file_exists($xslPath))
		{
			KalturaLog::err("XSL file not found [$xslPath]");
			return null;
		}
		$xsl = new DOMDocument();
		$xsl->load($xslPath);
			
		// set variables in the xsl
		$varNodes = $xsl->getElementsByTagName('variable');
		foreach($varNodes as $varNode)
		{
			$nameAttr = $varNode->attributes->getNamedItem('name');
			if(!$nameAttr)
				continue;
				
			$name = $nameAttr->value;
			if($name && $providerData->$name)
			{
				$varNode->textContent = $providerData->$name;
				$varNode->appendChild($xsl->createTextNode($providerData->$name));
				KalturaLog::debug("Set variable [$name] to [{$providerData->$name}]");
			}
		}

		$proc = new XSLTProcessor;
		$proc->registerPHPFunctions();
		$proc->importStyleSheet($xsl);
		
		$xml = $proc->transformToDoc($xml);
		if(!$xml)
		{
			KalturaLog::err("XML Transformation failed");
			return null;
		}
			
		$xsdPath = realpath(dirname(__FILE__) . '/../') . '/xml/submit.xsd';
		if(file_exists($xsdPath) && !$xml->schemaValidate($xsdPath))
		{
			KalturaLog::err("Schema validation failed");		
			return null;
		}
		
		return $xml;
	}
}
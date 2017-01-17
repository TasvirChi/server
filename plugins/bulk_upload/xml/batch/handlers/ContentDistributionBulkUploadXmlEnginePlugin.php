<?php
/**
 * @package plugins.contentDistributionBulkUploadXml
 */
class ContentDistributionBulkUploadXmlEnginePlugin extends BorhanPlugin implements IBorhanPending, IBorhanBulkUploadXmlHandler, IBorhanConfigurator
{
	const PLUGIN_NAME = 'contentDistributionBulkUploadXmlEngine';
	
	const BULK_UPLOAD_XML_VERSION_MAJOR = 1;
	const BULK_UPLOAD_XML_VERSION_MINOR = 0;
	const BULK_UPLOAD_XML_VERSION_BUILD = 0;
	
	const CONTENT_DSTRIBUTION_VERSION_MAJOR = 1;
	const CONTENT_DSTRIBUTION_VERSION_MINOR = 1;
	const CONTENT_DSTRIBUTION_VERSION_BUILD = 0;
	
	/**
	 * @var array<string, int> of distribution profiles by their system name
	 */
	private $distributionProfilesNames = null;
	
	/**
	 * @var array<string, int> of distribution profiles by their provider name
	 */
	private $distributionProfilesProviders = null;
	
	/**
	 * @var BulkUploadEngineXml
	 */
	private $xmlBulkUploadEngine = null;
	
	/**
	 * @return string
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$bulkUploadXmlVersion = new BorhanVersion(
			self::BULK_UPLOAD_XML_VERSION_MAJOR,
			self::BULK_UPLOAD_XML_VERSION_MINOR,
			self::BULK_UPLOAD_XML_VERSION_BUILD);
			
		$contentDistributionVersion = new BorhanVersion(
			self::CONTENT_DSTRIBUTION_VERSION_MAJOR,
			self::CONTENT_DSTRIBUTION_VERSION_MINOR,
			self::CONTENT_DSTRIBUTION_VERSION_BUILD);
			
		$bulkUploadXmlDependency = new BorhanDependency(BulkUploadXmlPlugin::getPluginName(), $bulkUploadXmlVersion);
		$contentDistributionDependency = new BorhanDependency(ContentDistributionPlugin::getPluginName(), $contentDistributionVersion);
		
		return array($bulkUploadXmlDependency, $contentDistributionDependency);
	}
	
	public function getDistributionProfileId($systemName, $providerName)
	{
		if(is_null($this->distributionProfilesNames))
		{
			$distributionPlugin = BorhanContentDistributionClientPlugin::get(KBatchBase::$kClient);
			$distributionProfileListResponse = $distributionPlugin->distributionProfile->listAction();
			if(!is_array($distributionProfileListResponse->objects))
				return null;
				
			$this->distributionProfilesNames = array();
			$this->distributionProfilesProviders = array();
			
			foreach($distributionProfileListResponse->objects as $distributionProfile)
			{
				if(!is_null($distributionProfile->systemName))
					$this->distributionProfilesNames[$distributionProfile->systemName] = $distributionProfile->id;
					
				if(!is_null($distributionProfile->providerType))
					$this->distributionProfilesProviders[$distributionProfile->providerType] = $distributionProfile->id;
			}
		}
		
		if(!empty($systemName) && isset($this->distributionProfilesNames[$systemName]))
			return $this->distributionProfilesNames[$systemName];
		
		if(!empty($providerName) && isset($this->distributionProfilesProviders[$providerName]))
			return $this->distributionProfilesProviders[$providerName];
			
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::configureBulkUploadXmlHandler()
	 */
	public function configureBulkUploadXmlHandler(BulkUploadEngineXml $xmlBulkUploadEngine)
	{
		$this->xmlBulkUploadEngine = $xmlBulkUploadEngine;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemAdded()
	 */
	public function handleItemAdded(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		if(!($object instanceof BorhanBaseEntry))
			return;
			
		if(empty($item->distributions))
			return;
			
		KBatchBase::impersonate($this->xmlBulkUploadEngine->getCurrentPartnerId());
		foreach($item->distributions->distribution as $distribution)
			$this->handleDistribution($object->id, $distribution);
		KBatchBase::unimpersonate();
	}
	
	protected function handleDistribution($entryId, SimpleXMLElement $distribution)
	{
		$distributionProfileId = null;
		if(!empty($distribution->distributionProfileId))
			$distributionProfileId = (int)$distribution->distributionProfileId;

		if(!$distributionProfileId && (!empty($distribution->distributionProfile) || !empty($distribution->distributionProvider)))
			$distributionProfileId = $this->getDistributionProfileId($distribution->distributionProfile, $distribution->distributionProvider);
				
		if(!$distributionProfileId)
			throw new BorhanBatchException("Unable to retrieve distributionProfileId value", BorhanBatchJobAppErrors::BULK_MISSING_MANDATORY_PARAMETER);
		
		$distributionPlugin = BorhanContentDistributionClientPlugin::get(KBatchBase::$kClient);
		
		$entryDistributionFilter = new BorhanEntryDistributionFilter();
		$entryDistributionFilter->distributionProfileIdEqual = $distributionProfileId;
		$entryDistributionFilter->entryIdEqual = $entryId;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 1;
		
		$entryDistributionResponse = $distributionPlugin->entryDistribution->listAction($entryDistributionFilter, $pager);
		
		$entryDistribution = new BorhanEntryDistribution();
		$entryDistributionId = null;
		if(is_array($entryDistributionResponse->objects) && count($entryDistributionResponse->objects) > 0)
		{
			$existingEntryDistribution = reset($entryDistributionResponse->objects);
			$entryDistributionId = $existingEntryDistribution->id;
		}
		else
		{
			$entryDistribution->entryId = $entryId;
			$entryDistribution->distributionProfileId = $distributionProfileId;
		}
		
		if(!empty($distribution->sunrise) && KBulkUploadEngine::isFormatedDate($distribution->sunrise))
			$entryDistribution->sunrise = KBulkUploadEngine::parseFormatedDate($distribution->sunrise);
			
		if(!empty($distribution->sunset) && KBulkUploadEngine::isFormatedDate($distribution->sunset))
			$entryDistribution->sunset = KBulkUploadEngine::parseFormatedDate($distribution->sunset);
		
		if(!empty($distribution->flavorAssetIds))
			$entryDistribution->flavorAssetIds = $distribution->flavorAssetIds;
		
		if(!empty($distribution->thumbAssetIds))
			$entryDistribution->thumbAssetIds = $distribution->thumbAssetIds;
			
		$submitWhenReady = false;
		if($distribution['submitWhenReady'])
			$submitWhenReady = true;
			
		if($entryDistributionId)
		{
			$updatedEntryDistribution = $distributionPlugin->entryDistribution->update($entryDistributionId, $entryDistribution);
			if($submitWhenReady && $updatedEntryDistribution->dirtyStatus == BorhanEntryDistributionFlag::UPDATE_REQUIRED)
				$distributionPlugin->entryDistribution->submitUpdate($entryDistributionId);
		}
		else
		{
			$createdEntryDistribution = $distributionPlugin->entryDistribution->add($entryDistribution);
			$distributionPlugin->entryDistribution->submitAdd($createdEntryDistribution->id, $submitWhenReady);
		}
	}

	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemUpdated()
	 */
	public function handleItemUpdated(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		$this->handleItemAdded($object, $item);
	}

	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemDeleted()
	 */
	public function handleItemDeleted(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		// No handling required
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanConfigurator::getConfig()
	 */
	public static function getConfig($configName)
	{
		if($configName == 'generator')
			return new Zend_Config_Ini(dirname(__FILE__) . '/config/contentDistributionBulkUploadXml.generator.ini');
			
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanConfigurator::getContainerName()
	*/
	public function getContainerName()
	{
		return 'distribution';
	}
}

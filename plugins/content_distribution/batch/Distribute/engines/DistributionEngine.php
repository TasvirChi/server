<?php
/**
 * @package plugins.contentDistribution 
 * @subpackage Scheduler.Distribute
 */
abstract class DistributionEngine implements IDistributionEngine
{	
	/**
	 * @var int
	 */
	protected $partnerId;
	
	/**
	 * @var string
	 */
	protected $tempDirectory = null;
	
	/**
	 * @param string $interface
	 * @param BorhanDistributionProviderType $providerType
	 * @param BorhanDistributionJobData $data
	 * @return DistributionEngine
	 */
	public static function getEngine($interface, $providerType, BorhanDistributionJobData $data)
	{
		$engine = null;
		if($providerType == BorhanDistributionProviderType::GENERIC)
		{
			$engine = new GenericDistributionEngine();
		}
		else
		{
			$engine = BorhanPluginManager::loadObject($interface, $providerType);
		}
		
		if($engine)
		{
			$engine->setClient();
			$engine->configure($data);
		}
		
		return $engine;
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionEngine::setClient()
	 */
	public function setClient()
	{
		$this->partnerId = KBatchBase::$kClient->getPartnerId();
	}
	
	/* (non-PHPdoc)
	 * @see IDistributionEngine::setClient()
	 */
	public function configure()
	{
		$this->tempDirectory = isset(KBatchBase::$taskConfig->params->tempDirectoryPath) ? KBatchBase::$taskConfig->params->tempDirectoryPath : sys_get_temp_dir();
		if (!is_dir($this->tempDirectory)) 
			kFile::fullMkfileDir($this->tempDirectory, 0700, true);
	}

	/**
	 * @param string $entryId
	 * @return BorhanMediaEntry
	 */
	protected function getEntry($partnerId, $entryId)
	{
		KBatchBase::impersonate($partnerId);
		$entry = KBatchBase::$kClient->baseEntry->get($entryId);
		KBatchBase::unimpersonate();
		
		return $entry;
	}

	/**
	 * @param string $flavorAssetIds comma seperated
	 * @return array<BorhanFlavorAsset>
	 */
	protected function getFlavorAssets($partnerId, $flavorAssetIds)
	{
		KBatchBase::impersonate($partnerId);
		$filter = new BorhanAssetFilter();
		$filter->idIn = $flavorAssetIds;
		$flavorAssetsList = KBatchBase::$kClient->flavorAsset->listAction($filter);
		KBatchBase::unimpersonate();
		
		return $flavorAssetsList->objects;
	}

	/**
	 * @param string $thumbAssetIds comma seperated
	 * @return array<BorhanThumbAsset>
	 */
	protected function getThumbAssets($partnerId, $thumbAssetIds)
	{
		KBatchBase::impersonate($partnerId);
		$filter = new BorhanAssetFilter();
		$filter->idIn = $thumbAssetIds;
		$thumbAssetsList = KBatchBase::$kClient->thumbAsset->listAction($filter);
		KBatchBase::unimpersonate();
		return $thumbAssetsList->objects;
	}

	/**
	 * @param string $thumbAssetId
	 * @return string url
	 */
	protected function getThumbAssetUrl($thumbAssetId)
	{
		$contentDistributionPlugin = BorhanContentDistributionClientPlugin::get(KBatchBase::$kClient);
		return $contentDistributionPlugin->contentDistributionBatch->getAssetUrl($thumbAssetId);
	
//		$domain = $this->borhanClient->getConfig()->serviceUrl;
//		return "$domain/api_v3/service/thumbAsset/action/serve/thumbAssetId/$thumbAssetId";
	}

	/**
	 * @param string $flavorAssetId
	 * @return string url
	 */
	protected function getFlavorAssetUrl($flavorAssetId)
	{
		$contentDistributionPlugin = BorhanContentDistributionClientPlugin::get(KBatchBase::$kClient);
		return $contentDistributionPlugin->contentDistributionBatch->getAssetUrl($flavorAssetId);
	}

	/**
	 * @param array<BorhanMetadata> $metadataObjects
	 * @param string $field
	 * @return array|string
	 */
	protected function findMetadataValue(array $metadataObjects, $field, $asArray = false)
	{
		$results = array();
		foreach($metadataObjects as $metadata)
		{
			$xml = new DOMDocument();
			$xml->loadXML($metadata->xml);
			$nodes = $xml->getElementsByTagName($field);
			foreach($nodes as $node)
				$results[] = $node->textContent;
		}
		
		if(!$asArray)
		{
			if(!count($results))
				return null;
				
			if(count($results) == 1)
				return reset($results);
		}
			
		return $results;
	}

	/**
	 * @param string $objectId
	 * @param BorhanMetadataObjectType $objectType
	 * @return array<BorhanMetadata>
	 */
	protected function getMetadataObjects($partnerId, $objectId, $objectType = BorhanMetadataObjectType::ENTRY, $metadataProfileId = null)
	{
		if(!class_exists('BorhanMetadata'))
			return null;
			
		KBatchBase::impersonate($partnerId);
		
		$metadataFilter = new BorhanMetadataFilter();
		$metadataFilter->objectIdEqual = $objectId;
		$metadataFilter->metadataObjectTypeEqual = $objectType;
		$metadataFilter->orderBy = BorhanMetadataOrderBy::CREATED_AT_DESC;
		
		if($metadataProfileId)
			$metadataFilter->metadataProfileIdEqual = $metadataProfileId;
		
		$metadataPager = new BorhanFilterPager();
		$metadataPager->pageSize = 1;
		$metadataListResponse = KBatchBase::$kClient->metadata->listAction($metadataFilter, $metadataPager);
		
		KBatchBase::unimpersonate();
		
		if(!$metadataListResponse->totalCount)
			throw new Exception("No metadata objects found");

		return $metadataListResponse->objects;
	}
}

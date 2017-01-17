<?php
/**
 * Add & Manage Syndication Feeds
 *
 * @service syndicationFeed
 * @package api
 * @subpackage services
 */
class SyndicationFeedService extends BorhanBaseService 
{
	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('asset');
		$this->applyPartnerFilterForClass('assetParams');
		$this->applyPartnerFilterForClass('assetParamsOutput');
		$this->applyPartnerFilterForClass('entry');
		$this->applyPartnerFilterForClass('syndicationFeed');
	}
	
	protected function partnerGroup($peer = null)
	{
		// required in order to load flavor params of partner zero
		if ($this->actionName == 'requestConversion')
			return parent::partnerGroup() . ',0';

		return parent::partnerGroup();
	}
	
	protected function borhanNetworkAllowed($actionName)
	{
		if ($actionName === 'get') {
			return true;
		}
		if ($actionName === 'list') {
			return true;
		}

		return parent::borhanNetworkAllowed($actionName);
	}
	
	/**
	 * Add new Syndication Feed
	 * 
	 * @action add
	 * @param BorhanBaseSyndicationFeed $syndicationFeed
	 * @return BorhanBaseSyndicationFeed 
	 */
	public function addAction(BorhanBaseSyndicationFeed $syndicationFeed)
	{
		$syndicationFeed->validatePlaylistId();
		$syndicationFeed->validateStorageId($this->getPartnerId());
		
		if ($syndicationFeed instanceof BorhanGenericXsltSyndicationFeed ){
			$syndicationFeed->validatePropertyNotNull('xslt');				
			$syndicationFeedDB = new genericSyndicationFeed();
			$syndicationFeedDB->incrementVersion();	
		}else
		{
			$syndicationFeedDB = new syndicationFeed();
		}
			
		$syndicationFeed->toInsertableObject($syndicationFeedDB);
		$syndicationFeedDB->setPartnerId($this->getPartnerId());
		$syndicationFeedDB->setStatus(BorhanSyndicationFeedStatus::ACTIVE);
		$syndicationFeedDB->save();
		
		if($syndicationFeed->addToDefaultConversionProfile)
		{
			
			$partner = PartnerPeer::retrieveByPK($this->getPartnerId());
		
			$c = new Criteria();
			$c->addAnd(flavorParamsConversionProfilePeer::CONVERSION_PROFILE_ID, $partner->getDefaultConversionProfileId());
			$c->addAnd(flavorParamsConversionProfilePeer::FLAVOR_PARAMS_ID, $syndicationFeed->flavorParamId);
			$is_exist = flavorParamsConversionProfilePeer::doCount($c);
			if(!$is_exist || $is_exist === 0)
			{
				$assetParams = assetParamsPeer::retrieveByPK($syndicationFeed->flavorParamId);
				
				$fpc = new flavorParamsConversionProfile();
				$fpc->setConversionProfileId($partner->getDefaultConversionProfileId());
				$fpc->setFlavorParamsId($syndicationFeed->flavorParamId);
				
				if($assetParams)
				{
					$fpc->setReadyBehavior($assetParams->getReadyBehavior());
					$fpc->setSystemName($assetParams->getSystemName());
					
					if($assetParams->hasTag(assetParams::TAG_SOURCE) || $assetParams->hasTag(assetParams::TAG_INGEST))
						$fpc->setOrigin(assetParamsOrigin::INGEST);
					else
						$fpc->setOrigin(assetParamsOrigin::CONVERT);
				}
				
				
				$fpc->save();
			}
		}
		
		if ($syndicationFeed instanceof BorhanGenericXsltSyndicationFeed ){
			$key = $syndicationFeedDB->getSyncKey(genericSyndicationFeed::FILE_SYNC_SYNDICATION_FEED_XSLT);
			kFileSyncUtils::file_put_contents($key, $syndicationFeed->xslt);
		}
		
		$syndicationFeed->fromObject($syndicationFeedDB, $this->getResponseProfile());
	
		return $syndicationFeed;
	}
	
	/**
	 * Get Syndication Feed by ID
	 * 
	 * @action get
	 * @param string $id
	 * @return BorhanBaseSyndicationFeed
	 * @throws BorhanErrors::INVALID_FEED_ID
	 */
	public function getAction($id)
	{
		$syndicationFeedDB = syndicationFeedPeer::retrieveByPK($id);
		if (!$syndicationFeedDB)
			throw new BorhanAPIException(BorhanErrors::INVALID_FEED_ID, $id);
			
		$syndicationFeed = BorhanSyndicationFeedFactory::getInstanceByType($syndicationFeedDB->getType());
		//echo $syndicationFeed->feedUrl; die;
		$syndicationFeed->fromObject($syndicationFeedDB, $this->getResponseProfile());
		return $syndicationFeed;
	}
        
	/**
	 * Update Syndication Feed by ID
	 * 
	 * @action update
	 * @param string $id
	 * @param BorhanBaseSyndicationFeed $syndicationFeed
	 * @return BorhanBaseSyndicationFeed
	 * @throws BorhanErrors::INVALID_FEED_ID
	 */
	public function updateAction($id, BorhanBaseSyndicationFeed $syndicationFeed)
	{
		$syndicationFeedDB = syndicationFeedPeer::retrieveByPK($id);
		if (!$syndicationFeedDB)
			throw new BorhanAPIException(BorhanErrors::INVALID_FEED_ID, $id);
		
		$syndicationFeed->validateStorageId($this->getPartnerId());
		$syndicationFeed->toUpdatableObject($syndicationFeedDB, array('type'));	
		
		if (($syndicationFeed instanceof BorhanGenericXsltSyndicationFeed) && ($syndicationFeed->xslt != null)){
			if(!($syndicationFeedDB instanceof genericSyndicationFeed))
				throw new BorhanAPIException(BorhanErrors::INVALID_FEED_TYPE, get_class($syndicationFeedDB));
				
			$syndicationFeedDB->incrementVersion();
		}
		$syndicationFeedDB->save();		
		
		
		if (($syndicationFeed instanceof BorhanGenericXsltSyndicationFeed) && ($syndicationFeed->xslt != null)){			
			$key = $syndicationFeedDB->getSyncKey(genericSyndicationFeed::FILE_SYNC_SYNDICATION_FEED_XSLT);
			kFileSyncUtils::file_put_contents($key, $syndicationFeed->xslt);
		}
		
        $syndicationFeed->type = null;
        
		$syndicationFeed = BorhanSyndicationFeedFactory::getInstanceByType($syndicationFeedDB->getType());
		$syndicationFeed->fromObject($syndicationFeedDB, $this->getResponseProfile());
		return $syndicationFeed;
	}
	
	/**
	 * Delete Syndication Feed by ID
	 * 
	 * @action delete
	 * @param string $id
	 * @throws BorhanErrors::INVALID_FEED_ID
	 */
	public function deleteAction($id)
	{
		$syndicationFeedDB = syndicationFeedPeer::retrieveByPK($id);
		if (!$syndicationFeedDB)
			throw new BorhanAPIException(BorhanErrors::INVALID_FEED_ID, $id);
		
		
		$syndicationFeedDB->setStatus(BorhanSyndicationFeedStatus::DELETED);
		$syndicationFeedDB->save();
	}
	
	/**
	 * List Syndication Feeds by filter with paging support
	 * 
	 * @action list
	 * @param BorhanBaseSyndicationFeedFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanBaseSyndicationFeedListResponse
	 */
	public function listAction(BorhanBaseSyndicationFeedFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if ($filter === null)
			$filter = new BorhanBaseSyndicationFeedFilter();
			
		if ($filter->orderBy === null)
			$filter->orderBy = BorhanBaseSyndicationFeedOrderBy::CREATED_AT_DESC;
			
		$syndicationFilter = new syndicationFeedFilter();
		
		$filter->toObject($syndicationFilter);

		$c = new Criteria();
		$syndicationFilter->attachToCriteria($c);
		$c->add(syndicationFeedPeer::DISPLAY_IN_SEARCH, mySearchUtils::DISPLAY_IN_SEARCH_SYSTEM, Criteria::NOT_EQUAL);
		
		$totalCount = syndicationFeedPeer::doCount($c);
                
        if($pager === null)
        	$pager = new BorhanFilterPager();
                
        $pager->attachToCriteria($c);
		$dbList = syndicationFeedPeer::doSelect($c);
		
		$list = BorhanBaseSyndicationFeedArray::fromDbArray($dbList, $this->getResponseProfile());
		$response = new BorhanBaseSyndicationFeedListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;
		
	}
	
	/**
	 * get entry count for a syndication feed
	 *
	 * @action getEntryCount
	 * @param string $feedId
	 * @return BorhanSyndicationFeedEntryCount
	 * @throws BorhanErrors::INVALID_FEED_ID
	 */
	public function getEntryCountAction($feedId)
	{
		$syndicationFeedDB = syndicationFeedPeer::retrieveByPK($feedId);
		if (!$syndicationFeedDB)
			throw new BorhanAPIException(BorhanErrors::INVALID_FEED_ID, $feedId);
		
		$feedCount = new BorhanSyndicationFeedEntryCount();
		
		$feedRenderer = new BorhanSyndicationFeedRenderer($feedId);
		$feedCount->totalEntryCount = $feedRenderer->getEntriesCount();
		
		$feedRenderer = new BorhanSyndicationFeedRenderer($feedId);
		$feedRenderer->addFlavorParamsAttachedFilter();
		$feedCount->actualEntryCount = $feedRenderer->getEntriesCount();
		
		$feedCount->requireTranscodingCount = $feedCount->totalEntryCount - $feedCount->actualEntryCount;
		
		return $feedCount;
	}
	
	/**
	 *  request conversion for all entries that doesnt have the required flavor param
	 *  returns a comma-separated ids of conversion jobs
	 *
	 *  @action requestConversion
	 *  @param string $feedId
	 *  @return string
	 * @throws BorhanErrors::INVALID_FEED_ID
	 */
	public function requestConversionAction($feedId)
	{
		$syndicationFeedDB = syndicationFeedPeer::retrieveByPK($feedId);
		if (!$syndicationFeedDB)
			throw new BorhanAPIException(BorhanErrors::INVALID_FEED_ID, $feedId);
			
		// find entry ids that already converted to the flavor
		$feedRendererWithTheFlavor = new BorhanSyndicationFeedRenderer($feedId);
		$feedRendererWithTheFlavor->addFlavorParamsAttachedFilter();
		$entriesWithTheFlavor = $feedRendererWithTheFlavor->getEntriesIds();
		
		// create filter of the entries that not converted
		$entryFilter = new entryFilter();
		$entryFilter->setIdNotIn($entriesWithTheFlavor);
		
		// create feed with the new filter
		$feedRendererToConvert = new BorhanSyndicationFeedRenderer($feedId);
		$feedRendererToConvert->addFilter($entryFilter);
		
		$createdJobsIds = array();
		$flavorParamsId = $feedRendererToConvert->syndicationFeed->flavorParamId;
		
		while($entry = $feedRendererToConvert->getNextEntry())
		{
			$originalFlavorAsset = assetPeer::retrieveOriginalByEntryId($entry->getId());
			if (!is_null($originalFlavorAsset))
			{
				$err = "";
				$job = kBusinessPreConvertDL::decideAddEntryFlavor(null, $entry->getId(), $flavorParamsId, $err);
				if($job && is_object($job))
					$createdJobsIds[] = $job->getId();
			}
		}
		return(implode(',', $createdJobsIds));
	}
}
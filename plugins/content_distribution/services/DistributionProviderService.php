<?php
/**
 * Distribution Provider service
 *
 * @service distributionProvider
 * @package plugins.contentDistribution
 * @subpackage api.services
 */
class DistributionProviderService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('GenericDistributionProvider');
		
		if(!ContentDistributionPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, ContentDistributionPlugin::PLUGIN_NAME);
	}
	
	
	/**
	 * List all distribution providers
	 * 
	 * @action list
	 * @param BorhanDistributionProviderFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDistributionProviderListResponse
	 */
	function listAction(BorhanDistributionProviderFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDistributionProviderFilter();
			
		$c = new Criteria();
		if($filter instanceof BorhanGenericDistributionProviderFilter)
		{
			$genericDistributionProviderFilter = new GenericDistributionProviderFilter();
			$filter->toObject($genericDistributionProviderFilter);
			
			$genericDistributionProviderFilter->attachToCriteria($c);
		}
		$count = GenericDistributionProviderPeer::doCount($c);
		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria($c);
		$list = GenericDistributionProviderPeer::doSelect($c);
		
		$response = new BorhanDistributionProviderListResponse();
		$response->objects = BorhanDistributionProviderArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
	
		$syndicationProvider = new BorhanSyndicationDistributionProvider();
		$syndicationProvider->fromObject(SyndicationDistributionProvider::get());
		$response->objects[] = $syndicationProvider;
		$response->totalCount++;
		
		$pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanContentDistributionProvider');
		foreach($pluginInstances as $pluginInstance)
		{
			$provider = $pluginInstance->getBorhanProvider();
			if($provider)
			{
				$response->objects[] = $provider;
				$response->totalCount++;
			}
		}
		
		return $response;
	}	
}

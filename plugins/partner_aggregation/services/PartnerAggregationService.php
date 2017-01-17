<?php
/**
 * Partner Aggregation service
 *
 * @service partnerAggregation
 * @package plugins.partnerAggregation
 * @subpackage api.services
 */
class PartnerAggregationService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		$this->applyPartnerFilterForClass('DwhHourlyPartner');
	}
	
	/**
	 * List aggregated partner data
	 * 
	 * @action list
	 * @param BorhanDwhHourlyPartnerFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDwhHourlyPartnerListResponse
	 */
	function listAction(BorhanDwhHourlyPartnerFilter $filter, BorhanFilterPager $pager = null)
	{
		$filter->validatePropertyNotNull('aggregatedTimeLessThanOrEqual');
		$filter->validatePropertyNotNull('aggregatedTimeGreaterThanOrEqual');

		if (!$pager)
			$pager = new BorhanFilterPager();
		
		$c = new Criteria();			
		$dwhHourlyPartnerFilter = $filter->toObject();
		$dwhHourlyPartnerFilter->attachToCriteria($c);
		$count = DwhHourlyPartnerPeer::doCount($c);
		$pager->attachToCriteria($c);
		$list = DwhHourlyPartnerPeer::doSelect($c);
		
		$response = new BorhanDwhHourlyPartnerListResponse();
		$response->objects = BorhanDwhHourlyPartnerArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
	
		return $response;
	}	
}

<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.filters
 */
class BorhanEntryDistributionFilter extends BorhanEntryDistributionBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new EntryDistributionFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$c = new Criteria();
		$entryDistributionFilter = $this->toObject();
		
		$entryDistributionFilter->attachToCriteria($c);
		$count = EntryDistributionPeer::doCount($c);
		
		$pager->attachToCriteria ( $c );
		$list = EntryDistributionPeer::doSelect($c);
		
		$response = new BorhanEntryDistributionListResponse();
		$response->objects = BorhanEntryDistributionArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $count;
	
		return $response;
	}
}

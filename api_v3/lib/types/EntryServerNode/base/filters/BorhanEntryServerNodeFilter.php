<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanEntryServerNodeFilter extends BorhanEntryServerNodeBaseFilter
{
	/**
	 * @return baseObjectFilter
	 */
	protected function getCoreFilter()
	{
		return new EntryServerNodeFilter();
	}

	/**
	 * @param BorhanFilterPager $pager
	 * @param BorhanDetachedResponseProfile $responseProfile
	 * @return BorhanListResponse
	 * @throws BorhanAPIException
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		if($this->entryIdEqual)
		{
			$entry = entryPeer::retrieveByPK($this->entryIdEqual);
			if(!$entry)
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $this->entryIdEqual);
		} 
		else if ($this->entryIdIn)
		{
			$entryIds = explode(',', $this->entryIdIn);
			$entries = entryPeer::retrieveByPKs($entryIds);
			
			$validEntryIds = array();
			foreach ($entries as $entry)
				$validEntryIds[] = $entry->getId();
			
			if (!count($validEntryIds))
			{
				return array(array(), 0);
			}
			
			$entryIds = implode($validEntryIds, ',');
			$this->entryIdIn = $entryIds;
		}

		$c = new Criteria();
		$entryServerNodeFilter = $this->toObject();
		$entryServerNodeFilter->attachToCriteria($c);
		$pager->attachToCriteria($c);

		$dbEntryServerNodes = EntryServerNodePeer::doSelect($c);

		$entryServerNodeList = BorhanEntryServerNodeArray::fromDbArray($dbEntryServerNodes, $responseProfile);
		$response = new BorhanEntryServerNodeListResponse();
		$response->objects = $entryServerNodeList;
		$response->totalCount = count($dbEntryServerNodes);
		return $response;
	}
}

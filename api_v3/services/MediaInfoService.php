<?php
/**
 * Media Info service
 *
 * @service mediaInfo
 * @package api
 * @subpackage services
 */
class MediaInfoService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('mediaInfo');
		$this->applyPartnerFilterForClass('asset');
    }
	
	/**
	 * List media info objects by filter and pager
	 * 
	 * @action list
	 * @param BorhanMediaInfoFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanMediaInfoListResponse
	 */
	function listAction(BorhanMediaInfoFilter $filter = null, BorhanFilterPager $pager = null)
	{
	    myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL2;
	    
		if (!$filter)
			$filter = new BorhanMediaInfoFilter();

		if (!$pager)
			$pager = new BorhanFilterPager();
			
		$mediaInfoFilter = new MediaInfoFilter();
		
		$filter->toObject($mediaInfoFilter);
		
		if ($filter->flavorAssetIdEqual)
		{
			// Since media_info table does not have partner_id column, enforce partner by getting the asset
			if (!assetPeer::retrieveById($filter->flavorAssetIdEqual))
				throw new BorhanAPIException(BorhanErrors::FLAVOR_ASSET_ID_NOT_FOUND, $filter->flavorAssetIdEqual);
		}

		$c = new Criteria();
		$mediaInfoFilter->attachToCriteria($c);
		
		$totalCount = mediaInfoPeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = mediaInfoPeer::doSelect($c);
		
		$list = BorhanMediaInfoArray::fromDbArray($dbList, $this->getResponseProfile());
		$response = new BorhanMediaInfoListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;
	}
}

<?php
/**
 * System user service
 *
 * @service fileSync
 * @package plugins.fileSync
 * @subpackage api.services
 */
class FileSyncService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		// since plugin might be using KS impersonation, we need to validate the requesting
		// partnerId from the KS and not with the $_POST one
		if(!FileSyncPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, FileSyncPlugin::PLUGIN_NAME);
	}
	
	/**
	 * List file syce objects by filter and pager
	 *
	 * @action list
	 * @param BorhanFileSyncFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanFileSyncListResponse
	 */
	function listAction(BorhanFileSyncFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanFileSyncFilter();

		if (!$pager)
			$pager = new BorhanFilterPager();
			
		$fileSyncFilter = new FileSyncFilter();
		
		$filter->toObject($fileSyncFilter);

		$c = new Criteria();
		$fileSyncFilter->attachToCriteria($c);
		
		$totalCount = FileSyncPeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = FileSyncPeer::doSelect($c);
		
		$list = BorhanFileSyncArray::fromDbArray($dbList, $this->getResponseProfile());
		$response = new BorhanFileSyncListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;
	}

	/**
	 * Update file sync by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanFileSync $fileSync
	 * @return BorhanFileSync
	 * 
	 * @throws FileSyncErrors::FILESYNC_ID_NOT_FOUND
	 */
	function updateAction($id, BorhanFileSync $fileSync)
	{
		$dbFileSync = FileSyncPeer::retrieveByPK($id);
		if (!$dbFileSync)
		{
			throw new BorhanAPIException(FileSyncErrors::FILESYNC_ID_NOT_FOUND, $id);
		}

		$fileSync->toUpdatableObject($dbFileSync);
		$dbFileSync->save();
		
		$fileSync = new BorhanFileSync();
		$fileSync->fromObject($dbFileSync, $this->getResponseProfile());
		return $fileSync;
	}
}

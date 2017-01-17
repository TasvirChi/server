<?php

/**
 *
 * @service liveReports
 * @package api
 * @subpackage services
 */
class LiveReportsService extends BorhanBaseService
{
	
	/**
	 * @action getEvents
	 * @param BorhanLiveReportType $reportType
	 * @param BorhanLiveReportInputFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanReportGraphArray
	 */
	public function getEventsAction($reportType,
			BorhanLiveReportInputFilter $filter = null,
			BorhanFilterPager $pager = null)
	{
		if(is_null($filter))
			$filter = new BorhanLiveReportInputFilter();
		if(is_null($pager))
			$pager = new BorhanFilterPager;
		
		$client = new WSLiveReportsClient();
		$wsFilter = $filter->getWSObject();
		$wsFilter->partnerId = kCurrentContext::getCurrentPartnerId();
		$wsPager = new WSLiveReportInputPager($pager->pageSize, $pager->pageIndex);
		
		$wsResult = $client->getEvents($reportType, $wsFilter, $wsPager);
		$resultsArray = array();
		$objects = explode(";", $wsResult->objects);
		foreach($objects as $object) {
			if(empty($object))
				continue;
			
			$parts = explode(",", $object);
			$additionalValue = "";
			if(count($parts) > 2)
				$additionalValue = "," . $parts[2];
			$resultsArray[$parts[0]] = $parts[1] . $additionalValue;
		}
		
		$kResult = BorhanReportGraphArray::fromReportDataArray(array("audience" => $resultsArray));
		
		return $kResult;
	}
	
	/**
	 * @action getReport
	 * @param BorhanLiveReportType $reportType
	 * @param BorhanLiveReportInputFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanLiveStatsListResponse
	 */
	public function getReportAction($reportType, 
			BorhanLiveReportInputFilter $filter = null,
			BorhanFilterPager $pager = null)
	{
		if(is_null($filter))
			$filter = new BorhanLiveReportInputFilter();
		if(is_null($pager))
			$pager = new BorhanFilterPager();
		
		$client = new WSLiveReportsClient();
		$wsFilter = $filter->getWSObject();
		$wsFilter->partnerId = kCurrentContext::getCurrentPartnerId();
		
		$wsPager = new WSLiveReportInputPager($pager->pageSize, $pager->pageIndex);
		
		switch($reportType) {
			case BorhanLiveReportType::ENTRY_GEO_TIME_LINE:
			case BorhanLiveReportType::ENTRY_SYNDICATION_TOTAL:
				return $this->requestClient($client, $reportType, $wsFilter, $wsPager);
				
			case BorhanLiveReportType::PARTNER_TOTAL:
				if($filter->live && empty($wsFilter->entryIds)) {
					$entryIds = $this->getAllLiveEntriesLiveNow();
					if(empty($entryIds)) {
						$response = new BorhanLiveStatsListResponse();
						$response->totalCount = 1;
						$response->objects = array();
						$response->objects[] = new BorhanLiveStats();
						return $response;
					}
					
					$wsFilter->entryIds = $entryIds;
				}
				return $this->requestClient($client, $reportType, $wsFilter, $wsPager);
				
			case BorhanLiveReportType::ENTRY_TOTAL:
				$totalCount = null;
				if(!$filter->live && empty($wsFilter->entryIds)) {
					list($entryIds, $totalCount) = $this->getLiveEntries($client, kCurrentContext::getCurrentPartnerId(), $pager);
					if(empty($entryIds))
						return new BorhanLiveStatsListResponse();

					$wsFilter->entryIds = implode(",", $entryIds);
				}
				
				/** @var BorhanLiveStatsListResponse */
				$result = $this->requestClient($client, $reportType, $wsFilter, $wsPager);
				if($totalCount)
					$result->totalCount = $totalCount;

				if ($entryIds) {
					$this->sortResultByEntryIds($result, $entryIds);
				}
				return $result;
		}
		
	}
	
	/**
	 * @action exportToCsv
	 * @param BorhanLiveReportExportType $reportType 
	 * @param BorhanLiveReportExportParams $params
	 * @return BorhanLiveReportExportResponse
	 */
	public function exportToCsvAction($reportType, BorhanLiveReportExportParams $params)
	{
		if(!$params->recpientEmail) {
			$kuser = kCurrentContext::getCurrentKsKuser();
			if($kuser) {
				$params->recpientEmail = $kuser->getEmail();
			} else {
				$partnerId = kCurrentContext::getCurrentPartnerId();
				$partner = PartnerPeer::retrieveByPK($partnerId);
				$params->recpientEmail = $partner->getAdminEmail();
			}
		}
		
		// Validate input
		if($params->entryIds) {
			$entryIds = explode(",", $params->entryIds);
			$entries = entryPeer::retrieveByPKs($entryIds);
			if(count($entryIds) != count($entries))
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $params->entryIds);
		}
		
		
		$dbBatchJob = kJobsManager::addExportLiveReportJob($reportType, $params);
		
		$res = new BorhanLiveReportExportResponse();
		$res->referenceJobId = $dbBatchJob->getId();
		$res->reportEmail = $params->recpientEmail;
		
		return $res;
	}
	
	/**
	 *
	 * Will serve a requested report
	 * @action serveReport
	 *
	 *
	 * @param string $id - the requested id
	 * @return string
	 */
	public function serveReportAction($id) {
		
		$fileNameRegex = "/^(?<dc>[01]+)_(?<fileName>\\d+_Export_[a-zA-Z0-9]+_[\\w\\-]+.csv)$/";
	
		// KS verification - we accept either admin session or download privilege of the file
		$ks = $this->getKs();
		if(!$ks || !($ks->isAdmin() || $ks->verifyPrivileges(ks::PRIVILEGE_DOWNLOAD, $id)))
			KExternalErrors::dieError(KExternalErrors::ACCESS_CONTROL_RESTRICTED);
	
		if(!preg_match($fileNameRegex, $id, $matches)) {
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);
		}
		
		// Check if the request should be handled by the other DC
		$curerntDc = kDataCenterMgr::getCurrentDcId();
		if($matches['dc'] == 1 - $curerntDc)
			kFileUtils::dumpApiRequest ( kDataCenterMgr::getRemoteDcExternalUrlByDcId ( 1 - $curerntDc ) );
		
		// Serve report
		$filePath = $this->getReportDirectory( $this->getPartnerId()) . DIRECTORY_SEPARATOR . $matches['fileName'];
		return $this->dumpFile($filePath, 'text/csv');
	}
	
	protected function getReportDirectory($partnerId) {
		$folderPath = "/content/reports/live/$partnerId";
		$directory =  myContentStorage::getFSContentRootPath() . $folderPath;
		if(!file_exists($directory))
			mkdir($directory);
		return $directory;
	}
	
	/**
	 * Returns all live entry ids that are live now by partner id 
	 */
	protected function getAllLiveEntriesLiveNow() {
		// Partner ID condition is embeded in the default criteria.
		$baseCriteria = BorhanCriteria::create(entryPeer::OM_CLASS);
		$filter = new entryFilter();
		$filter->setTypeEquel(BorhanEntryType::LIVE_STREAM);
		$filter->setIsLive(true);
		$filter->setPartnerSearchScope(baseObjectFilter::MATCH_BORHAN_NETWORK_AND_PRIVATE);
		$filter->attachToCriteria($baseCriteria);
		
		$entries = entryPeer::doSelect($baseCriteria);
		$entryIds = array();
		foreach($entries as $entry)
			$entryIds[] = $entry->getId();
		
		return implode(",", $entryIds);
	}
	
	/**
	 * Returns all live entries that were live in the past X hours
	 */
	protected function getLiveEntries(WSLiveReportsClient $client, $partnerId, BorhanFilterPager $pager) {
		// Get live entries list
		/** @var WSLiveEntriesListResponse */
		$response = $client->getLiveEntries($partnerId);
		
		if($response->totalCount == 0)
			return null;
		
		// Hack to overcome the bug of single value
		$entryIds = $response->entries;
		if(!is_array($entryIds)) {
			$entryIds = array();
			$entryIds[] = $response->entries;
		}

		// Order entries by first broadcast
		$baseCriteria = BorhanCriteria::create(entryPeer::OM_CLASS);
		$filter = new entryFilter();
		$filter->setTypeEquel(BorhanEntryType::LIVE_STREAM);
		$filter->setIdIn($entryIds);
		$filter->setPartnerSearchScope(baseObjectFilter::MATCH_BORHAN_NETWORK_AND_PRIVATE);
		$baseCriteria->addAscendingOrderByColumn(entryPeer::NAME);
		$filter->attachToCriteria($baseCriteria);
		$pager->attachToCriteria($baseCriteria);
		
		$entries = entryPeer::doSelect($baseCriteria);
		$entryIds = array();
		foreach($entries as $entry)
			$entryIds[] = $entry->getId();
		
		$totalCount = $baseCriteria->getRecordsCount();
		return array($entryIds, $totalCount);
	}
	
	protected function requestClient(WSLiveReportsClient $client, $reportType, $wsFilter, $wsPager) {
		/** @var WSLiveStatsListResponse */
		$result = $client->getReport($reportType, $wsFilter, $wsPager);
		$kResult = $result->toBorhanObject();
		return $kResult;
	}

	/**
	 * Sorts the objects array in the result object according to the order of entryIds provided
	 * @param $result
	 * @param $entryIds
	 */
	protected function sortResultByEntryIds($result, $entryIds)
	{
		$resultHash = array();
		foreach ($result->objects as $object) {
			$resultHash[$object->entryId] = $object;
		}

		$result->objects = array();
		foreach ($entryIds as $entryId) {
			if ($resultHash[$entryId]) {
				$result->objects[] = $resultHash[$entryId];
			}
		}
	}
}


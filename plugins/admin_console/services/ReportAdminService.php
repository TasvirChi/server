<?php
/**
 * @service reportAdmin
 * @package plugins.adminConsole
 * @subpackage api.services
 */
class ReportAdminService extends BorhanBaseService
{
    /* (non-PHPdoc)
     * @see BorhanBaseService::initService()
     */
    public function initService($serviceId, $serviceName, $actionName)
    {
        parent::initService($serviceId, $serviceName, $actionName);

		if(!AdminConsolePlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, AdminConsolePlugin::PLUGIN_NAME);
	}
    
	/**
	 * @action add
	 * @param BorhanReport $report
	 * @return BorhanReport
	 */
	function addAction(BorhanReport $report)
	{
		$dbReport = new Report();
		$report->toInsertableObject($dbReport);
		$dbReport->save();
		
		$report->fromObject($dbReport, $this->getResponseProfile());
		return $report;
	}
	
	/**
	 * @action get
	 * @param int $id
	 * @return BorhanReport
	 */
	function getAction($id)
	{
		$dbReport = ReportPeer::retrieveByPK($id);
		if (is_null($dbReport))
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);
			
		$report = new BorhanReport();
		$report->fromObject($dbReport, $this->getResponseProfile());
		return $report;
	}
	
	/**
	 * @action list
	 * @param BorhanReportFilter $filter
	 * @param BorhanReport $report
	 * @return BorhanReportListResponse
	 */
	function listAction(BorhanReportFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanReportFilter();
			
		if (!$pager)
			$pager = new BorhanFilterPager();
			
		$reportFilter = new ReportFilter();
		
		$filter->toObject($reportFilter);
		$c = new Criteria();
		$reportFilter->attachToCriteria($c);
		$pager->attachToCriteria($c);
		
		$dbList = ReportPeer::doSelect($c);
		$c->setLimit(null);
		$totalCount = ReportPeer::doCount($c);

		$list = BorhanReportArray::fromDbArray($dbList, $this->getResponseProfile());
		$response = new BorhanReportListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;    
	}
	
	/**
	 * @action update
	 * @param int $id
	 * @param BorhanReport $report
	 * @return BorhanReport
	 */
	function updateAction($id, BorhanReport $report)
	{
		$dbReport = ReportPeer::retrieveByPK($id);
		if (is_null($dbReport))
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);
			
		$report->toUpdatableObject($dbReport);
		$dbReport->save();
		
		$report->fromObject($dbReport, $this->getResponseProfile());
		return $report;
	}
	
	/**
	 * @param int $id
	 * @action delete
	 */
	function deleteAction($id)
	{
		$dbReport = ReportPeer::retrieveByPK($id);
		if (is_null($dbReport))
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);
			
		$dbReport->setDeletedAt(time());
		$dbReport->save();
	}
	
	/**
	 * @action executeDebug
	 * @param int $id
	 * @param BorhanKeyValueArray $params
	 * @return BorhanReportResponse
	 */
	function executeDebugAction($id, BorhanKeyValueArray $params = null)
	{
		$dbReport = ReportPeer::retrieveByPK($id);
		if (is_null($dbReport))
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);
			
		$query = $dbReport->getQuery();
		$matches = null;
		$execParams = BorhanReportHelper::getValidateExecutionParameters($dbReport, $params);
		
		try 
		{
			$kReportsManager = new kReportManager($dbReport);
			list($columns, $rows) = $kReportsManager->execute($execParams);
		}
		catch(Exception $ex)
		{
			BorhanLog::err($ex);
			throw new BorhanAPIException(BorhanErrors::INTERNAL_SERVERL_ERROR_DEBUG, $ex->getMessage());
		}
		
		$reportResponse = BorhanReportResponse::fromColumnsAndRows($columns, $rows);
		
		return $reportResponse;
	}
	
	/**
	 * @action getParameters
	 * @param int $id
	 * @return BorhanStringArray
	 */
	function getParametersAction($id)
	{
		$dbReport = ReportPeer::retrieveByPK($id);
		if (is_null($dbReport))
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);
			
		return BorhanStringArray::fromStringArray($dbReport->getParameters());
	}
	
	/**
	 * @action getCsvUrl
	 * @param int $id
	 * @param int $reportPartnerId
	 * @return string
	 */
	function getCsvUrlAction($id, $reportPartnerId)
	{
		$dbReport = ReportPeer::retrieveByPK($id);
		if (is_null($dbReport))
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_FOUND, $id);

		$dbPartner = PartnerPeer::retrieveByPK($reportPartnerId);
		if (is_null($dbPartner))
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $reportPartnerId);

		// allow creating urls for reports that are associated with partner 0 and the report owner
		if ($dbReport->getPartnerId() !== 0 && $dbReport->getPartnerId() !== $reportPartnerId) 
			throw new BorhanAPIException(BorhanErrors::REPORT_NOT_PUBLIC, $id); 
		
		$ks = new ks();
		$ks->valid_until = time() + 2 * 365 * 24 * 60 * 60; // 2 years 
		$ks->type = ks::TYPE_KS;
		$ks->partner_id = $reportPartnerId;
		$ks->master_partner_id = null;
		$ks->partner_pattern = $reportPartnerId;
		$ks->error = 0;
		$ks->rand = microtime(true);
		$ks->user = '';
		$ks->privileges = 'setrole:REPORT_VIEWER_ROLE';
		$ks->additional_data = null;
		$ks_str = $ks->toSecureString();
		
		$paramsArray = $this->getParametersAction($id);
		$paramsStrArray = array();
		foreach($paramsArray as $param)
		{
			$paramsStrArray[] = ($param->value.'={'.$param->value.'}');
		}

		$url = "http://" . kConf::get("www_host") . "/api_v3/index.php/service/report/action/getCsvFromStringParams/id/{$id}/ks/" . $ks_str . "/params/" . implode(';', $paramsStrArray);
		return $url;
	}
}
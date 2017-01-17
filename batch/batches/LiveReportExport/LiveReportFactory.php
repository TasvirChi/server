<?php

class LiveReportFactory {
	
	public function getExporter($type, BorhanLiveReportExportJobData $jobData) {
		
		$exporter = null;
		switch ($type) {
			case BorhanLiveReportExportType::PARTNER_TOTAL_ALL :
				$exporter = new PartnerTotalAllExporter($jobData);
				break;
			case BorhanLiveReportExportType::PARTNER_TOTAL_LIVE :
				$exporter = new PartnerTotalLiveExporter($jobData);
				break;
			case BorhanLiveReportExportType::ENTRY_TIME_LINE_ALL :
				$exporter = new EntryTimeLineAllExporter($jobData);
				break;
			case BorhanLiveReportExportType::ENTRY_TIME_LINE_LIVE :
				$exporter = new EntryTimeLineLiveExporter ($jobData);
				break;
			case BorhanLiveReportExportType::LOCATION_ALL :
				$exporter = new LocationAllExporter($jobData);
				break;
			case BorhanLiveReportExportType::LOCATION_LIVE :
				$exporter = new LocationLiveExporter($jobData);
				break;
			case BorhanLiveReportExportType::SYNDICATION_ALL :
				$exporter = new SyndicationAllExporter($jobData);
				break;
			case BorhanLiveReportExportType::SYNDICATION_LIVE :
				$exporter = new SyndicationLiveExporter($jobData);
				break;
			default:
				throw new KOperationEngineException("Unknown Exporter type : " . $type);
		}
		
		$exporter->init($jobData);
		
		return $exporter;
	}
}

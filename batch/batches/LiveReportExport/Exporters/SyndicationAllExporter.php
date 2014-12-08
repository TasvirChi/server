<?php

class SyndicationAllExporter extends LiveReportEntryExporter {

	public function __construct(KalturaLiveReportExportJobData $data) {
		parent::__construct($data, "referrers-@ENTRY_ID@-%s-%s.csv", LiveReportConstants::SECONDS_36_HOURS);
	}

	protected function getEngines() {
		return array_merge(
			array(
					new LiveReportConstantStringEngine("Report Type:". LiveReportConstants::CELLS_SEPARATOR ."Referrers of pure live (%s)",
							 array(LiveReportConstants::ENTRY_IDS)),
					new LiveReportConstantStringEngine(LiveReportConstants::ROWS_SEPARATOR),
					new LiveReportConstantStringEngine("Time Range:". LiveReportConstants::CELLS_SEPARATOR ."%s", array(self::TIME_RANGE))),
			$this->allEntriesEngines,
			array(new LiveReportReferrerEngine())
		);
	}
}
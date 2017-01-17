<?php 

abstract class LiveReportQueryHelper {
	
	/**
	 * Executes a simple report query and returns the result as array of <key, value> according to the specified fields. 
	 * @param BorhanLiveReportType $reportType The type of the report
	 * @param BorhanLiveReportInputFilter $filter The input filter for the report
	 * @param BorhanFilterPager $pager The pager of the report
	 * @param string $keyField The name of the field that will be used as key
	 * @param string $valueField The name of the field that will be used as value
	 */
	public static function retrieveFromReport($reportType,
			BorhanLiveReportInputFilter $filter = null,
			BorhanFilterPager $pager = null,
			$keyField = null,
			$valueField) {
		
		$result = KBatchBase::$kClient->liveReports->getReport($reportType, $filter, $pager);
		if($result->totalCount == 0)
			return array();
		
		$res = array();
		if (is_array($valueField)) {
			foreach($result->objects as $object) {
				foreach($valueField as $singleValueField) {
					if($keyField) {
						if (empty($res[$object->$keyField])) {
							$res[$object->$keyField] = array();
						}
						$res[$object->$keyField][$singleValueField] = $object->$singleValueField;
					}
					else {
						if (empty($res[$singleValueField])) {
							$res[$singleValueField] = array();
						}
						$res[$singleValueField][] = $object->$singleValueField;
					}
				}
			}
		}
		else {
			foreach($result->objects as $object) {
				if($keyField)
					$res[$object->$keyField] = $object->$valueField;
				else
					$res[] = $object->$valueField;
			}
		}
		return $res;
	}

	/**
	 * Executes a simple events query and returns the result as string according to the specified key.
	 * @param BorhanLiveReportType $reportType The type of the report
	 * @param BorhanLiveReportInputFilter $filter The input filter for the report
	 * @param BorhanFilterPager $pager The pager of the report
	 * @param string $keyField The name of the field that will be used as key
	 */
	public static function getEvents($reportType,
			BorhanLiveReportInputFilter $filter = null,
			BorhanFilterPager $pager = null,
			$keyField) {
		
		$results = KBatchBase::$kClient->liveReports->getEvents($reportType, $filter, $pager);
		foreach($results as $result) {
			if($result->id == $keyField) {
				return $result->data;
			}
		}
		return null;
	}
}
<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanReportResponse extends BorhanObject 
{
	/**
	 * @var string
	 */
	public $columns;
	
	/**
	 * @var BorhanStringArray
	 */
	public $results;
	
	public static function fromColumnsAndRows($columns, $rows)
	{
		$reportResponse = new BorhanReportResponse();
		$reportResponse->columns = implode(',', $columns);
		$reportResponse->results = new BorhanStringArray();
		foreach($rows as $row)
		{
			// we are using comma as a seperator, so don't allow it in results
			foreach($row as &$tempColumnData)
				$tempColumnData = str_replace(',', '', $tempColumnData);
				
			$string = new BorhanString();
			$string->value = implode(',', $row);
			$reportResponse->results[] = $string;
		}
		return $reportResponse;
	}
}
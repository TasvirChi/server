<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanReportGraphArray extends BorhanTypedArray
{
	public static function fromReportDataArray ( $arr )
	{
		$newArr = new BorhanReportGraphArray();
		foreach ( $arr as $id => $data )
		{
			$nObj = new BorhanReportGraph();
			$nObj->fromReportData ( $id, $data );
			$newArr[] = $nObj;
		}
			
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanReportGraph" );
	}
}
?>
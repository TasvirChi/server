<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanReportBaseTotalArray extends BorhanTypedArray
{
	public static function fromReportDataArray ( $arr )
	{
		$newArr = new BorhanReportBaseTotalArray();
		foreach ( $arr as $id => $data )
		{
			$nObj = new BorhanReportBaseTotal();
			$nObj->fromReportData ( $id, $data );
			$newArr[] = $nObj;
		}
			
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanReportBaseTotal" );
	}
}
?>
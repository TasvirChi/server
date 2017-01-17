<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPartnerArray extends BorhanTypedArray
{
	public static function fromPartnerArray(array $arr)
	{
		$newArr = new BorhanPartnerArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanPartner();
			$nObj->fromPartner($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanPartner" );
	}
}
?>
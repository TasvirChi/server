<?php
/**
 * @package plugins.systemPartner
 * @subpackage api.objects
 */
class BorhanSystemPartnerPackageArray extends BorhanTypedArray
{
	public function __construct()
	{
		return parent::__construct("BorhanSystemPartnerPackage");
	}
	
	public function fromArray($arr)
	{
		foreach($arr as $item)
		{
			$obj = new BorhanSystemPartnerPackage();
			$obj->fromArray($item);
			$this[] = $obj;
		}
	}
}
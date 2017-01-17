<?php
/**
 * @package plugins.systemPartner
 * @subpackage api.objects
 */
class BorhanSystemPartnerUsageArray extends BorhanTypedArray
{
	public function __construct()
	{
		return parent::__construct("BorhanSystemPartnerUsageItem");
	}
}
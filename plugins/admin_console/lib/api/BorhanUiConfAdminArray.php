<?php
/**
 * @package plugins.adminConsole
 * @subpackage api.objects
 */
class BorhanUiConfAdminArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanUiConfAdminArray();
		foreach ( $arr as $obj )
		{
			$nObj = new BorhanUiConfAdmin();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanUiConfAdmin");
	}
}

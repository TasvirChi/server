<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPermissionItemArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanPermissionItemArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			if ($obj->getType() == PermissionItemType::API_ACTION_ITEM) {
				$nObj = new BorhanApiActionPermissionItem();
			}
			else if ($obj->getType() == PermissionItemType::API_PARAMETER_ITEM) {
				$nObj = new BorhanApiParameterPermissionItem();
			}
			else {
				BorhanLog::crit('Unknown permission item type ['.$obj->getType().'] defined with id ['.$obj->getId().'] - skipping!');
				continue;
			}
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct('BorhanPermissionItem');	
	}
}

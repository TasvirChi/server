<?php
/**
 * @package plugins.audit
 * @subpackage api.objects
 */
class BorhanAuditTrailChangeItemArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanAuditTrailChangeItemArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
    		$nObj = new BorhanAuditTrailChangeItem();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanAuditTrailChangeItem");	
	}
	
	public function toObjectArray()
	{
		$ret = array();
		
		foreach($this as $item)
			$ret[] = $item->toObject();
			
		return $ret;
	}
}

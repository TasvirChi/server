<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.objects
 */
class BorhanBusinessProcessServerArray extends BorhanTypedArray
{
	public static function fromDbArray($arr)
	{
		$newArr = new BorhanBusinessProcessServerArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			/* @var $obj BusinessProcessServer */
    		$nObj = BorhanBusinessProcessServer::getInstanceByType($obj->getType());
    		if(!$nObj)
    		{
    			BorhanLog::err("Business-Process server could not find matching type for [" . $obj->getType() . "]");
    			continue;
    		}
			$nObj->fromObject($obj);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanBusinessProcessServer");	
	}
}
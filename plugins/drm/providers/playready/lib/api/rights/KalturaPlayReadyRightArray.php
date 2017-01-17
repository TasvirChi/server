<?php
/**
 * @package plugins.playReady
 * @subpackage api.objects
 */
class BorhanPlayReadyRightArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanPlayReadyRightArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = self::getInstanceByDbObject($obj);
			if($nObj)
			{
				$nObj->fromObject($obj, $responseProfile);
				$newArr[] = $nObj;
			}
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanPlayReadyRight");	
	}
	
	private static function getInstanceByDbObject($obj)
	{
		if($obj instanceof PlayReadyCopyRight)
			return new BorhanPlayReadyCopyRight();
		if($obj instanceof PlayReadyPlayRight)
			return new BorhanPlayReadyPlayRight();
			
		return null;
	}
}
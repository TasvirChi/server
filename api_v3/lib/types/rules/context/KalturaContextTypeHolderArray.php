<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanContextTypeHolderArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanContextTypeHolderArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $type)
		{
			$nObj = self::getInstanceByType($type);				
			$nObj->type = $type;
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}

	static function getInstanceByType($type)
	{
		switch($type)
		{
			case ContextType::DOWNLOAD:
			case ContextType::PLAY:
			case ContextType::THUMBNAIL:
			case ContextType::METADATA:
				return new BorhanAccessControlContextTypeHolder();
			default:
				return new BorhanContextTypeHolder();
		}		
	}
	
	public function __construct()
	{
		parent::__construct("BorhanContextTypeHolder");	
	}
}
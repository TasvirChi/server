<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanResponseProfileMappingArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanResponseProfileMappingArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$dbClass = get_class($obj);
			if ($dbClass == 'kResponseProfileMapping')
				$nObj = new BorhanResponseProfileMapping();
			else
				$nObj = BorhanPluginManager::loadObject('BorhanResponseProfileMapping', $dbClass);

			if (is_null($nObj))
				BorhanLog::err('Failed to load api object for '.$dbClass);

			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanResponseProfileMapping");	
	}
}
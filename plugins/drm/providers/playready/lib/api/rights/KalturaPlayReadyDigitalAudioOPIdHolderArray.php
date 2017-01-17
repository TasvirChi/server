<?php
/**
 * @package plugins.playReady
 * @subpackage api.objects
 */
class BorhanPlayReadyDigitalAudioOPIdHolderArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanPlayReadyDigitalAudioOPIdHolderArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $type)
		{
    		$nObj = new BorhanPlayReadyDigitalAudioOPIdHolder();
			$nObj->type = $type;
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanPlayReadyDigitalAudioOPIdHolder");	
	}
}